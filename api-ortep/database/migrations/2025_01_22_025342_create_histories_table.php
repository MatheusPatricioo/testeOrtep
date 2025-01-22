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
        Schema::create('histories', function (Blueprint $table) {
            $table->id(); // Chave primária
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Relacionamento com a tabela users
            $table->string('word'); // Palavra visualizada
            $table->timestamp('viewed_at')->useCurrent(); // Data e hora da visualização
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};
