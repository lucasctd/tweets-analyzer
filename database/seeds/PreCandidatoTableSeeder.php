<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PreCandidatoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $precandidatos = [
            [
                'id' => 1,
                'nome' => 'Geraldo Alckmin',
                'partido' => 'PSDB'
            ],
            [
                'id' => 2,
                'nome' => 'Jair Bolsonaro',
                'partido' => 'PSL'
            ],
            [
                'id' => 3,
                'nome' => 'Manuela D\'Ávila',
                'partido' => 'PC do B'
            ],
            [
                'id' => 4,
                'nome' => 'Marina Silva',
                'partido' => 'Rede'
            ],
            [
                'id' => 5,
                'nome' => 'Ciro Gomes',
                'partido' => 'PDT'
            ],
            [
                'id' => 6,
                'nome' => 'João Amoêdo',
                'partido' => 'Novo'
            ]
        ];
        foreach ($precandidatos as $precandidato){
            DB::table('precandidato')->insert($precandidato);
        }
    }
}
