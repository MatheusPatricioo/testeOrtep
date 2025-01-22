<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // ID do usuário que visualizou a palavra
        'word',    // Palavra visualizada
        'viewed_at', // Data e hora da visualização
    ];

    public $timestamps = false; // Desativar timestamps automáticos, já que to usando 'viewed_at'
}
