<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\UserController;

// Rota inicial para verificar o funcionamento da API
Route::get('/', function () {
    return response()->json(['message' => 'Fullstack Challenge 🏅 - Dictionary']);
});

// Rotas de autenticação
Route::post('/auth/signup', [AuthController::class, 'signup']);
Route::post('/auth/signin', [AuthController::class, 'signin']);

// Rotas protegidas por autenticação
Route::middleware('auth:api')->group(function () {
    // Rotas relacionadas ao dicionário
    Route::get('/entries/en', [EntryController::class, 'index']); // Lista palavras com busca e paginação
    Route::get('/entries/en/{word}', [EntryController::class, 'show']); // Busca detalhes de uma palavra
    Route::post('/entries/en/{word}/favorite', [EntryController::class, 'favorite']); // Adiciona palavra aos favoritos
    Route::delete('/entries/en/{word}/unfavorite', [EntryController::class, 'unfavorite']); // Remove palavra dos favoritos

    // Rotas relacionadas ao usuário autenticado
    Route::get('/user/me', [UserController::class, 'me']); // Retorna o perfil do usuário autenticado
    Route::get('/user/me/history', [UserController::class, 'history']); // Lista histórico de palavras visualizadas
    Route::get('/user/me/favorites', [UserController::class, 'favorites']); // Lista palavras favoritas do usuário
});
