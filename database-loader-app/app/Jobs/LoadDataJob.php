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

class LoadDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $count;
    public $query;
    public $id;

    /**
     * Create a new job instance.
     *
     * @param $query
     * @param $count
     * @param $id
     */
    public function __construct($query, $count, $id)
    {
        $this->query = $query;
        $this->count = $count;
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @param Client $client
     * @return void
     */
    public function handle(Client $client)
    {
        $url = 'https://api.programmer.com.br/Twitter?query=' . $this->encodeUrl($this->query) . '&count=' . $this->count;
        try{
            event(new LoadDataStatusEvent('Carregando Dados...', $this->id));
            $response = $client->request('GET', $url,
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjgwYzc3YjJiNTA3MDg0MjZmOThmOTQ0NGUxOTMxZmQ3ZGVkYWFlOWM4OWM1NzlkYWI5ZTFiMDcyYjc1M2FlMjc2MmNhYTVhNWE5ODQ1NGE4In0.eyJhdWQiOiIyMyIsImp0aSI6IjgwYzc3YjJiNTA3MDg0MjZmOThmOTQ0NGUxOTMxZmQ3ZGVkYWFlOWM4OWM1NzlkYWI5ZTFiMDcyYjc1M2FlMjc2MmNhYTVhNWE5ODQ1NGE4IiwiaWF0IjoxNTI2Nzc0NDgxLCJuYmYiOjE1MjY3NzQ0ODEsImV4cCI6MTU1ODMxMDQ4MSwic3ViIjoiMSIsInNjb3BlcyI6W119.L2p_NR0SOsGmiVSDXdMmLKLcQT1u7chNKnl5ZD2TxzF2p1F8gwzmRHO5PNlBIdl1A_agPf6kFXthmHeHYvWtQyd8qADRQjMVwp5DUSbUh1wAKPRBLZsIn7ZahK2lePMz6L8s-t_Pp9Bc5766X6dJrNzTDEsTdMPo0rx8GyzK3oJw6q-4j9C_8BEh0k6clQ0Wq0H5oJN422AWW3C1fOsogO3byFHP8cPshQn5ws4nbF68gofncD2pfX9Em1UxW4WAlCJBt80g_rdIQMm78DDetWGcKPU3YUk5foTpOMnhMs8CjjC7KDDQeHk3i9d-1VljWvaOgLUdEqyYpeq2QhTQhJUVjLbHViymOVCfe8Rx-PEpjaQqVQJ5KZ06xuu2PX_nNe8P8iu3mKjPS5Hn3SQL43lqmZqgiDTdO1peeAkgF0V4hJHNSbs93B_v0XApP2Q7KPtr531zRLuPVv6uwKrywZ2a1pC8Ajaw6_2UKUgaEpXt_bU1iHRMvaB9uIJVyhGc2WBUl6Sipd6L6W-B8EdX8pFiHgaNnPbzq3WCWX94jnwAHXPhWklroa0ECslH9vPK-Jh2qEv3Y27LFFLmyNb5EPOGc_zTTSg7k_xL8Cu5Edi_LpspSzHuezwouHAWQtfU5h0CC1mofLECTo4cdJvozNbz1c5j_d7TuyKlDhLHYow',
                    ]
                ]);
            event(new LoadDataStatusEvent('Dados Carregados!', $this->id));
            $data = (object) json_decode($response->getBody(), true);
            SaveDataJob::dispatch($data, $this->query, $this->id);
        }catch (GuzzleException $e) {
            event(new LoadDataStatusEvent('Ocorreu um erro ao buscar os dados em api.programmer.com.br. Favor verificar o log da aplicação para mais detalhes.', $this->id));
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
        }
    }
	
	private function encodeUrl($query){
		$query = str_replace('#','%23', $query);
		return str_replace('@','%40', $query);
	}
}
