<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Importação da classe DB
use Illuminate\Support\Facades\Cache; // Importação da classe Cache
use GuzzleHttp\Client;
use App\Models\Favorite;

class EntryController extends Controller
{
    // Método para listar palavras (exemplo com busca e paginação)
    public function index(Request $request)
    {
        $query = $request->input('search');
        $limit = $request->input('limit', 10);

        // Gerar uma chave única para identificar os dados no cache
        $cacheKey = "entries_en_" . md5("search={$query}&limit={$limit}");

        // Medir o tempo de execução da requisição
        $startTime = microtime(true);

        // Verificar se os dados estão no cache
        $cachedData = Cache::get($cacheKey);

        if ($cachedData) {
            // Dados encontrados no cache (HIT)
            $responseTime = round((microtime(true) - $startTime) * 1000, 2);
            return response()->json($cachedData)
                ->header('x-cache', 'HIT')
                ->header('x-response-time', "{$responseTime}ms");
        }

        // Caso não esteja no cache, buscar no banco de dados (MISS)
        $words = DB::table('words')
            ->when($query, function ($queryBuilder) use ($query) {
                return $queryBuilder->where('word', 'like', "%$query%");
            })
            ->paginate($limit);

        $responseData = [
            'results' => collect($words->items())->pluck('word'), // Apenas as palavras
            'totalDocs' => $words->total(),
            'page' => $words->currentPage(),
            'totalPages' => $words->lastPage(),
            'hasNext' => $words->hasMorePages(),
            'hasPrev' => $words->currentPage() > 1,
        ];

        // Salvar os dados no cache por 10 minutos
        Cache::put($cacheKey, $responseData, now()->addMinutes(10));

        // Retornar os dados com os headers apropriados
        $responseTime = round((microtime(true) - $startTime) * 1000, 2);
        return response()->json($responseData)
            ->header('x-cache', 'MISS')
            ->header('x-response-time', "{$responseTime}ms");
    }

    // Método para buscar detalhes de uma palavra na API externa
    public function show($word)
    {
        $client = new Client();

        try {
            // Fazendo a requisição para a API externa
            $response = $client->get("https://api.dictionaryapi.dev/api/v2/entries/en/{$word}");
            $data = json_decode($response->getBody(), true);

            // Salvar no histórico do usuário autenticado
            if (auth('api')->check()) { // Verifica se o usuário está autenticado com o guard 'api'
                auth('api')->user()->history()->create([
                    'word' => $word,
                    'viewed_at' => now(),
                ]);
            }

            return response()->json($data);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Word not found'], 404);
        }
    }

    // Método para adicionar uma palavra aos favoritos
    public function favorite($word)
    {
        Favorite::create([
            'user_id' => auth('api')->id(),
            'word' => $word,
        ]);
    
        return response()->json(['message' => 'Word added to favorites'], 201);
    }
    
    public function unfavorite($word)
    {
        Favorite::where('user_id', auth('api')->id())
            ->where('word', $word)
            ->delete();
    
        return response()->json(['message' => 'Word removed from favorites'], 200);
    }
}
