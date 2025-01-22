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
        Schema::create('favorite_words', function (Blueprint $table) {
            $table->id(); // Chave primária
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relacionamento com a tabela users
            $table->string('word'); // Palavra favorita
            $table->timestamp('added_at')->useCurrent(); // Data e hora da adição aos favoritos
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('favorite_words'); // Exclui a tabela se existir
    }
    
};
