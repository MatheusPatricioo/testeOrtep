<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class ImportWords extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:words';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import words from words_dictionary.json into the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Caminho do arquivo JSON
        $path = base_path('words_dictionary.json');

        if (!File::exists($path)) {
            $this->error("Arquivo words_dictionary.json não encontrado.");
            return;
        }

        // Ler e decodificar o JSON
        $words = json_decode(File::get($path), true);

        if (!$words) {
            $this->error("Erro ao decodificar o arquivo JSON.");
            return;
        }

        // Inserir palavras no banco de dados
        foreach ($words as $word => $value) {
            DB::table('words')->updateOrInsert(['word' => $word]);
        }

        $this->info("Importação concluída com sucesso!");
    }
}
