<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    // Função para criar novo usuário
    
    public function signup(Request $request)
    {
        // Valida os dados antes de criar o usuário
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        // Cria o usuário no banco e faz hash da senha
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        // Gera o token ( deixa o usuario logado)
        $token = JWTAuth::fromUser($user);

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'token' => "Bearer {$token}",
        ], 201);
    }

    // Função de login - checa email e senha
    public function signin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Se o login falhar, retorna erro
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        // Pega os dados do usuário que acabou de logar
        $user = auth()->user();

        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'token' => "Bearer {$token}",
        ]);
    }
}
