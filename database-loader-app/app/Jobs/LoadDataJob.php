<?php

namespace App\Jobs;

use App\Events\LoadDataStatusEvent;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\PreCandidato;

class LoadDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $id;
    public $request;

    /**
     * Create a new job instance.
     *
     * @param $query
     * @param $count
     * @param $id
     */
    public function __construct(Request $request, $id)
    {
        $this->id = $id;
        $this->request = $request;
    }

    /**
     * Execute the job.
     *
     * @param Client $client
     * @return void
     */
    public function handle(Client $client)
    {
        //$url = 'https://api.programmer.com.br/Twitter?query=' . $this->encodeUrl($this->query) . '&count=' . $this->count;
        if($this->request->premium === '1'){
            $url = 'http://api.programmer.com.br.wazzu/TwitterPremium30?query=' . $this->encodeUrl($this->request->precandidato) . '&count=' . $this->request->count
                    .'&fromDate='. $this->request->fromDate.'&toDate='.$this->request->toDate.'&XDEBUG_SESSION_START=vscode';
        }else{
            $url = 'http://api.programmer.com.br.wazzu/Twitter?query=' . $this->encodeUrl($this->request->precandidato) . '&count=' . $this->request->count
            .'&XDEBUG_SESSION_START=vscode';
        }
        try{
            event(new LoadDataStatusEvent('Carregando Dados...', $this->id));
            $response = $client->request('GET', $url,
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        //'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjgwYzc3YjJiNTA3MDg0MjZmOThmOTQ0NGUxOTMxZmQ3ZGVkYWFlOWM4OWM1NzlkYWI5ZTFiMDcyYjc1M2FlMjc2MmNhYTVhNWE5ODQ1NGE4In0.eyJhdWQiOiIyMyIsImp0aSI6IjgwYzc3YjJiNTA3MDg0MjZmOThmOTQ0NGUxOTMxZmQ3ZGVkYWFlOWM4OWM1NzlkYWI5ZTFiMDcyYjc1M2FlMjc2MmNhYTVhNWE5ODQ1NGE4IiwiaWF0IjoxNTI2Nzc0NDgxLCJuYmYiOjE1MjY3NzQ0ODEsImV4cCI6MTU1ODMxMDQ4MSwic3ViIjoiMSIsInNjb3BlcyI6W119.L2p_NR0SOsGmiVSDXdMmLKLcQT1u7chNKnl5ZD2TxzF2p1F8gwzmRHO5PNlBIdl1A_agPf6kFXthmHeHYvWtQyd8qADRQjMVwp5DUSbUh1wAKPRBLZsIn7ZahK2lePMz6L8s-t_Pp9Bc5766X6dJrNzTDEsTdMPo0rx8GyzK3oJw6q-4j9C_8BEh0k6clQ0Wq0H5oJN422AWW3C1fOsogO3byFHP8cPshQn5ws4nbF68gofncD2pfX9Em1UxW4WAlCJBt80g_rdIQMm78DDetWGcKPU3YUk5foTpOMnhMs8CjjC7KDDQeHk3i9d-1VljWvaOgLUdEqyYpeq2QhTQhJUVjLbHViymOVCfe8Rx-PEpjaQqVQJ5KZ06xuu2PX_nNe8P8iu3mKjPS5Hn3SQL43lqmZqgiDTdO1peeAkgF0V4hJHNSbs93B_v0XApP2Q7KPtr531zRLuPVv6uwKrywZ2a1pC8Ajaw6_2UKUgaEpXt_bU1iHRMvaB9uIJVyhGc2WBUl6Sipd6L6W-B8EdX8pFiHgaNnPbzq3WCWX94jnwAHXPhWklroa0ECslH9vPK-Jh2qEv3Y27LFFLmyNb5EPOGc_zTTSg7k_xL8Cu5Edi_LpspSzHuezwouHAWQtfU5h0CC1mofLECTo4cdJvozNbz1c5j_d7TuyKlDhLHYow',
                        'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6Ijg3MTMzZDg1ZTUwNzAyYzY4OGZkZWRlYTEzZjU0MjY0MjU1MjU3NTY5YTgxMjYzMmYxZjJiMTFhOTFmNzNjMTc3ZTY2MWMyZTJhNjZhMTIyIn0.eyJhdWQiOiI0IiwianRpIjoiODcxMzNkODVlNTA3MDJjNjg4ZmRlZGVhMTNmNTQyNjQyNTUyNTc1NjlhODEyNjMyZjFmMmIxMWE5MWY3M2MxNzdlNjYxYzJlMmE2NmExMjIiLCJpYXQiOjE1MjY3NjI5NTUsIm5iZiI6MTUyNjc2Mjk1NSwiZXhwIjoxNTU4Mjk4OTU1LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.PzQEqzIZ-7lbYmO1kHcWY8dS4nN9auG_ZAzv7lQTl7dy5OXT4_7CFmdUZT7bTo94Ox1reoQUnirWoKmPPOVYrAiHktOcDTy6GQ-nukmd0gy_RzJQTiMJshYblNaMc_2UjiGBi2b7kTv8UQqpDX43lzM3es9qvDty_Nypl0Sib4FCtzkg-V4lqIHi6XFo7CSfGxmVeuhS8hRHHGoTqQj5yRTgJPaPcNME81u4wIMagt_m5bK4COoxAJBDrXfKMpt6u7ac3J-8KpzMoOKY_GU8jlqtVVXukUDNLuWcUqn6dey-jSimCn1pKHlfNTqVi9iRwmGJ_e9mizrdiPWoKBQVveTx5rtfP8lhh_-Xhr_FasHeVrbDQ7vPIdZ8YhIuO078TdWf-UIKmOutU4rfnRvJ81Wdu8OYQzz-_YUnkMbf3FxnghaAKGSTpjFUOvGNvp_kJ0kLSw5XlAbIkJUUcWSN4ezcaxs59RJBbLwL96cWHh8O9YZIoUlpA91Qbos2kpC-9V4Juho_rnInall6iOlEo8HOJHzm2shrDL4WQHZVCPOc-QfGt4GoyIe9sGUjLDSVxmkbzCpZjJxLbDv-fUXxFo2NoHP3pqx2HrkTjQ57pOXYU7rcus0duA31Vmd3fRc9SGG_lDqLiKKzVKal-TJlOEzonIa2S0NuDc1kX0y3pVE',
                    ]
                ]);
            event(new LoadDataStatusEvent('Dados Carregados!', $this->id));
            $data = (object) json_decode($response->getBody(), true);
            SaveDataJob::dispatchNow($data, $this->request->precandidato,$this->id);
        }catch (GuzzleException $e) {
            event(new LoadDataStatusEvent('Ocorreu um erro ao buscar os dados em api.programmer.com.br. 
            Favor verificar o log da aplicação para mais detalhes. O job foi colocado na fila novamente com delay de 900 segundos.', $this->id));
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            //$this->release(900); //adicionar a fila dnv
        }
    }
	
	private function encodeUrl($precandidato){
        $hashtags = PreCandidato::find($precandidato)->hashtags()->where('primary', true)->get();
        $query = $hashtags[0]->name;
        for($x = 1; $x < count($hashtags); $x++){
            $query.= '%20OR%20'.$hashtags[$x]->name;
        }
		return str_replace('#','%23', $query);
	}
}
