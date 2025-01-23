<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    // Mostra os dados do usuário logado
    public function me()
    {
        return response()->json(auth()->user());
    }

    // Mostra o histórico de palavras que o usuário já viu
    public function history(Request $request)
    {
        $limit = $request->input('limit', 10); // Quantos itens mostrar por página

        // Pega o histórico ordenado do mais recente pro mais antigo
        $history = auth()->user()->history()
            ->orderBy('viewed_at', 'desc')
            ->paginate($limit);

        // Organiza os dados pra retornar certinho
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

    // Mostra as palavras favoritas do usuário
    public function favorites(Request $request)
    {
        $limit = $request->input('limit', 10); // Quantos favoritos mostrar por página

        // Pega os favoritos, mais recentes primeiro
        $favorites = auth()->user()->favorites()
            ->orderBy('created_at', 'desc')
            ->paginate($limit);

        // Organiza do mesmo jeito que o histórico pra manter um padrão
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
