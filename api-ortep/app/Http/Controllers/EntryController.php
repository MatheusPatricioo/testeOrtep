<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Models\Favorite;

class EntryController extends Controller
{
    // Método para listar palavras (exemplo com busca e paginação)
    public function index(Request $request)
    {
        $query = $request->input('search');
        $limit = $request->input('limit', 10);

        // Simulação de busca no banco ou na API externa
        $words = Favorite::where('word', 'like', "%$query%")
            ->paginate($limit);

        return response()->json($words);
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
