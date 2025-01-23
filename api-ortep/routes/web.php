<?php

use Illuminate\Support\Facades\Route;

// Rota pra acessar a documentação OpenAPI da nossa API
Route::get('/api-docs', function () {
    return view('docs/index.html');
});
