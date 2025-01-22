<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dictionary', function (Blueprint $table) {
            $table->id(); // Chave primária
            $table->string('word')->unique(); // Palavra única no dicionário
            $table->json('data'); // Dados JSON relacionados à palavra (etimologia, sinônimos, etc.)
            $table->timestamps(); // Campos created_at e updated_at
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('dictionary'); // Exclui a tabela se existir
    }
    
};
