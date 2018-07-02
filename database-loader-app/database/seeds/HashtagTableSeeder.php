<?php

use Illuminate\Database\Seeder;

class HashtagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $precandidatos = [
            "1" => [
                'alckmin', 'geraldoalckmin', 'alckmin2018', 'alckminpresidente', 'geraldoalckminpresidente', 'AlckminNaJovemPan'
            ],
            "2" => [
                'jairbolsonaro', 'bolsonaro', 'bolsonaro2018', 'jairbolsonaropresidente', 'bolsonaropresidente', 'BolsonaroNaJovemPan'
            ],
            "3" => [
                'manueladavila', 'manueladavila2018', 'manuela2018', 'manueladavilapresidente', 'manuelapresidente'
            ],
            "4" => [
                'marina2018', 'marinasilva', 'marinasilva2018', 'marinapresidente', 'marinasilvapresidente', 'MarinaNaJovemPan'
            ],
            "5" => [
                'cirogomes2018', 'ciro2018', 'cirogomes', 'ciropresidente', 'cirogomespresidente','CiroGomesNaJovemPan'
            ],
            "6" => [
                'joaoamoedo', 'joaoamoedo2018', 'amoedo2018', 'joaoamoedopresidente', 'amoedopresidente', 'JoaoAmoedoNaJovemPan'
            ]
        ];
        
        foreach ($precandidatos as $precandidato => $hastags){
            foreach ($hastags as $hastag){
                DB::table('hashtag')->insert([
                    'name' => '#'.$hastag,
                    'precandidato_id' => $precandidato,
                    'primary' => true,
                ]);
            }
        }
    }
}
