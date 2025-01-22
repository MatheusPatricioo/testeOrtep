<?php

return [

'defaults' => [
    'guard' => 'api', // Define o guard padrão como 'api'
    'passwords' => 'users',
],

'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],

    'api' => [
        'driver' => 'jwt', // Certifique-se de que o driver é JWT
        'provider' => 'users',
    ],
],


    'providers' => [
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class, // Modelo de usuário
        ],
    ],

    'passwords' => [
        'users' => [
            'provider' => 'users',
            'table' => env('AUTH_PASSWORD_RESET_TOKEN_TABLE', 'password_reset_tokens'),
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),

];
