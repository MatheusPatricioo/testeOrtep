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
    public function history()
    {
        $history = auth()->user()->history()
            ->orderBy('viewed_at', 'desc')
            ->get();

        return response()->json($history);
    }

    // Listar palavras favoritas do usuário
    public function favorites()
    {
        $favorites = auth()->user()->favorites()
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($favorites);
    }
}
