<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LoadDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'load-database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Carrega os dados de estado, cidade, views e backup no banco de dados';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		$this->info('Inserindo dados de estados');
        DB::unprepared(file_get_contents('/home/vagrant/Code/tweets-analyzer.wazzu/scripts/insert_estados.sql'));
		
		$this->info('Inserindo dados de latitude e longitude dos estados');
		DB::unprepared(file_get_contents('/home/vagrant/Code/tweets-analyzer.wazzu/scripts/update_br_state_latitude_longitude.sql'));
		
		$this->info('Inserindo dados de cidade');
		DB::unprepared(file_get_contents('/home/vagrant/Code/tweets-analyzer.wazzu/scripts/insert_cidades.sql'));
				
		$this->info('Criando view "v_hashtags_secundarias"');
		DB::unprepared(file_get_contents('/home/vagrant/Code/tweets-analyzer.wazzu/scripts/v_hashtags_secundarias.sql'));
		
		$this->info('Criando view "v_localizacao_usuarios"');
		DB::unprepared(file_get_contents('/home/vagrant/Code/tweets-analyzer.wazzu/scripts/v_localizacao_usuarios.sql'));
		
		$this->info('Criando view "v_tweets_por_candidato"');
		DB::unprepared(file_get_contents('/home/vagrant/Code/tweets-analyzer.wazzu/scripts/v_tweets_por_candidato.sql'));
		
    }
}
