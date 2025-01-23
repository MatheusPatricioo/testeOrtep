<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Client;
use App\Models\Favorite;

class EntryController extends Controller
{
    // Lista as palavras e permite buscar por uma específica
    public function index(Request $request)
    {
        $query = $request->input('search');
        $limit = $request->input('limit', 10);

        // Cria uma chave única pro cache
        $cacheKey = "entries_en_" . md5("search={$query}&limit={$limit}");
        $startTime = microtime(true);

        // Tenta pegar do cache primeiro
        $cachedData = Cache::get($cacheKey);

        if ($cachedData) {
            $responseTime = round((microtime(true) - $startTime) * 1000, 2);
            return response()->json($cachedData)
                ->header('x-cache', 'HIT')
                ->header('x-response-time', "{$responseTime}ms");
        }

        // Se não achou no cache, busca no banco
        $words = DB::table('words')
            ->when($query, function ($queryBuilder) use ($query) {
                return $queryBuilder->where('word', 'like', "%$query%");
            })
            ->paginate($limit);

        // Organiza os dados pra retornar
        $responseData = [
            'results' => collect($words->items())->pluck('word'),
            'totalDocs' => $words->total(),
            'page' => $words->currentPage(),
            'totalPages' => $words->lastPage(),
            'hasNext' => $words->hasMorePages(),
            'hasPrev' => $words->currentPage() > 1,
        ];

        // Guarda no cache pra próxima vez
        Cache::put($cacheKey, $responseData, now()->addMinutes(10));

        $responseTime = round((microtime(true) - $startTime) * 1000, 2);
        return response()->json($responseData)
            ->header('x-cache', 'MISS')
            ->header('x-response-time', "{$responseTime}ms");
    }

    // Busca os detalhes de uma palavra específica
    public function show($word)
    {
        $client = new Client();

        try {
            // Busca na API do dicionário
            $response = $client->get("https://api.dictionaryapi.dev/api/v2/entries/en/{$word}");
            $data = json_decode($response->getBody(), true);

            // Se o usuário tiver logado, salva no histórico dele
            if (auth('api')->check()) {
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

    // Adiciona uma palavra aos favoritos do usuário
    public function favorite($word)
    {
        Favorite::create([
            'user_id' => auth('api')->id(),
            'word' => $word,
        ]);
    
        return response()->json(['message' => 'Word added to favorites'], 201);
    }
    
    // Remove uma palavra dos favoritos
    public function unfavorite($word)
    {
        Favorite::where('user_id', auth('api')->id())
            ->where('word', $word)
            ->delete();
    
        return response()->json(['message' => 'Word removed from favorites'], 200);
    }
}
