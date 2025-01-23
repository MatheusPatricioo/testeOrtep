<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\UserController;

// Rota principal - só pra ver se a API tá funcionando
Route::get('/', function () {
    return response()->json(['message' => 'Fullstack Challenge 🏅 - Dictionary']);
});

// Rotas que não precisam de login
Route::post('/auth/signup', [AuthController::class, 'signup']); // Criar conta
Route::post('/auth/signin', [AuthController::class, 'signin']); // Fazer login

// Rotas que precisam de login pra funcionar
Route::middleware('auth:api')->group(function () {
    // Rotas do dicionário
    Route::get('/entries/en', [EntryController::class, 'index']); // Lista todas as palavras
    Route::get('/entries/en/{word}', [EntryController::class, 'show']); // Busca uma palavra
    Route::post('/entries/en/{word}/favorite', [EntryController::class, 'favorite']); // Salva nos favoritos
    Route::delete('/entries/en/{word}/unfavorite', [EntryController::class, 'unfavorite']); // Tira dos favoritos

    // Rotas do usuário
    Route::get('/user/me', [UserController::class, 'me']); // Dados do usuário
    Route::get('/user/me/history', [UserController::class, 'history']); // Palavras que já viu
    Route::get('/user/me/favorites', [UserController::class, 'favorites']); // Palavras favoritas
});
