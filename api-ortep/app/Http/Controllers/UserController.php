<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    // Retornar o perfil do usuário autenticado
    public function me()
    {
        return response()->json(auth()->user());
    }

    // Listar o histórico de palavras visualizadas pelo usuário
    public function history(Request $request)
    {
        $limit = $request->input('limit', 10); // Número de itens por página (padrão: 10)

        // Buscar histórico do usuário autenticado com paginação
        $history = auth()->user()->history()
            ->orderBy('viewed_at', 'desc')
            ->paginate($limit);

        // Formatação da resposta
        return response()->json([
            'results' => collect($history->items())->map(function ($item) {
                return [
                    'word' => $item['word'],
                    'added' => $item['viewed_at'],
                ];
            }),
            'totalDocs' => $history->total(),
            'page' => $history->currentPage(),
            'totalPages' => $history->lastPage(),
            'hasNext' => $history->hasMorePages(),
            'hasPrev' => $history->currentPage() > 1,
        ]);
    }

    // Listar palavras favoritas do usuário
    public function favorites(Request $request)
    {
        $limit = $request->input('limit', 10); // Número de itens por página (padrão: 10)

        // Buscar favoritos do usuário autenticado com paginação
        $favorites = auth()->user()->favorites()
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        // Formatação da resposta
        return response()->json([
            'results' => collect($favorites->items())->map(function ($item) {
                return [
                    'word' => $item['word'],
                    'added' => $item['created_at'],
                ];
            }),
            'totalDocs' => $favorites->total(),
            'page' => $favorites->currentPage(),
            'totalPages' => $favorites->lastPage(),
            'hasNext' => $favorites->hasMorePages(),
            'hasPrev' => $favorites->currentPage() > 1,
        ]);
    }
}
