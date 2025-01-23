<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportWords extends Command
{
    protected $signature = 'import:words';
    protected $description = 'Import words from words_dictionary.json into the database';

    public function handle()
    {
        $this->info('Iniciando importação das palavras...');
        
        // Caminho do arquivo JSON
        $path = base_path('words_dictionary.json');

        if (!File::exists($path)) {
            $this->error("Arquivo words_dictionary.json não encontrado!");
            return 1;
        }

        // Ler e decodificar o JSON
        $this->info('Lendo arquivo JSON...');
        $words = json_decode(File::get($path), true);

        if (!$words) {
            $this->error("Erro ao decodificar o arquivo JSON!");
            return 1;
        }

        $total = count($words);
        $this->info("Total de palavras encontradas: {$total}");

        // Criar barra de progresso
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        // Inserir palavras no banco de dados
        $count = 0;
        foreach ($words as $word => $value) {
            DB::table('words')->updateOrInsert(['word' => $word]);
            $count++;
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Importação concluída! {$count} palavras importadas com sucesso.");
        
        return 0;
    }
}
