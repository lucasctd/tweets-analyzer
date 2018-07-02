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

    public $premium;
    public $count;
    public $precandidatoId;
    public $fromDate;
    public $toDate;
    public $id;

    public $timeout = 300;

    /**
     * Create a new job instance.
     *
     * @param $query
     * @param $count
     * @param $id
     */
    public function __construct($premium, $count, $precandidatoId, $fromDate, $toDate, $id)
    {
        $this->premium = $premium;
        $this->count = $count;
        $this->precandidatoId = $precandidatoId;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
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
        if($this->premium === '1'){
            $url = 'http://api.programmer.com.br.wazzu/TwitterPremiumFull?query=' . $this->getHashtags($this->precandidatoId) . '&count=' . $this->count
                    .'&fromDate='. $this->fromDate.'&toDate='.$this->toDate; //.'&XDEBUG_SESSION_START=vscode';
        }else{
            $url = 'http://api.programmer.com.br.wazzu/Twitter?query=' . $this->getHashtags($this->precandidatoId) . '&count=' . $this->count . '&until=' . $this->toDate;
            //.'&XDEBUG_SESSION_START=vscode';
        }
        try{
            event(new LoadDataStatusEvent('Carregando Dados...', $this->id));
            $response = $client->request('GET', $url,
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        //'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjgwYzc3YjJiNTA3MDg0MjZmOThmOTQ0NGUxOTMxZmQ3ZGVkYWFlOWM4OWM1NzlkYWI5ZTFiMDcyYjc1M2FlMjc2MmNhYTVhNWE5ODQ1NGE4In0.eyJhdWQiOiIyMyIsImp0aSI6IjgwYzc3YjJiNTA3MDg0MjZmOThmOTQ0NGUxOTMxZmQ3ZGVkYWFlOWM4OWM1NzlkYWI5ZTFiMDcyYjc1M2FlMjc2MmNhYTVhNWE5ODQ1NGE4IiwiaWF0IjoxNTI2Nzc0NDgxLCJuYmYiOjE1MjY3NzQ0ODEsImV4cCI6MTU1ODMxMDQ4MSwic3ViIjoiMSIsInNjb3BlcyI6W119.L2p_NR0SOsGmiVSDXdMmLKLcQT1u7chNKnl5ZD2TxzF2p1F8gwzmRHO5PNlBIdl1A_agPf6kFXthmHeHYvWtQyd8qADRQjMVwp5DUSbUh1wAKPRBLZsIn7ZahK2lePMz6L8s-t_Pp9Bc5766X6dJrNzTDEsTdMPo0rx8GyzK3oJw6q-4j9C_8BEh0k6clQ0Wq0H5oJN422AWW3C1fOsogO3byFHP8cPshQn5ws4nbF68gofncD2pfX9Em1UxW4WAlCJBt80g_rdIQMm78DDetWGcKPU3YUk5foTpOMnhMs8CjjC7KDDQeHk3i9d-1VljWvaOgLUdEqyYpeq2QhTQhJUVjLbHViymOVCfe8Rx-PEpjaQqVQJ5KZ06xuu2PX_nNe8P8iu3mKjPS5Hn3SQL43lqmZqgiDTdO1peeAkgF0V4hJHNSbs93B_v0XApP2Q7KPtr531zRLuPVv6uwKrywZ2a1pC8Ajaw6_2UKUgaEpXt_bU1iHRMvaB9uIJVyhGc2WBUl6Sipd6L6W-B8EdX8pFiHgaNnPbzq3WCWX94jnwAHXPhWklroa0ECslH9vPK-Jh2qEv3Y27LFFLmyNb5EPOGc_zTTSg7k_xL8Cu5Edi_LpspSzHuezwouHAWQtfU5h0CC1mofLECTo4cdJvozNbz1c5j_d7TuyKlDhLHYow',
                        'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjU3OGFmNzJmODM2YjgzODA4N2YzZGMxMjA4YTBjY2Y4ZjVhNzczYjM4MmQ5NjFlNjhmMzRmZjgwMjc0N2FiYTY4Y2RmNGNmZWJhMDY4YWI1In0.eyJhdWQiOiIxIiwianRpIjoiNTc4YWY3MmY4MzZiODM4MDg3ZjNkYzEyMDhhMGNjZjhmNWE3NzNiMzgyZDk2MWU2OGYzNGZmODAyNzQ3YWJhNjhjZGY0Y2ZlYmEwNjhhYjUiLCJpYXQiOjE1Mjk0NzU5NjcsIm5iZiI6MTUyOTQ3NTk2NywiZXhwIjoxNTYxMDExOTY3LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.EGT7dTwbALLOtxIfyHXx80AaTA3hFGhfe3N_Rti18KkmwOiMUx0_V7OAexqJ4tR9eL37TjsAnzVYNq-v_IG7OTlcYFXYl-V7oiGsm817ucy1Ny_2iNZGAuX5x3cbyCAVWs97rAYguBAF-IlxmUrL0TMK0KOT7gZwFWDcv6GSTjT1KRAr-nEHdi7fvKLgzBp5yHadkIop-KwJ6b6H0-HoxHlss2i3MU1c6RoviUUKtkGhddMa-qal_afMjCdUmAk6rjVPpaaoizxkrE1I024oToWkEhnq46ASQsT3CbPzHNlaXXT9-emO_HD1f9io-fSOM8b_MTni8MHvgeH9_daYsyLKejLTsVLSBG_I8mel6qeHoHj8TG-YGggFGqHv8eC7-KHlacp6nL6QODa7SGbU4zMLiHS8yEPbrJQALn_tZCsuQEpSIuBivM0-pJkxcZgJqHdlh3dM3vg7U28k8bwJtM1-eQ5lMOFDKZWQCRWQmzYmbJZL_ezAIY7L2jP9cY2Th4HltRYJ7Tqr1CJk4sdiWRLOhro0j7akTlIM-hoDFS1DAG0wOS3oMwZHmohWYpnpwhUAXddamL2jdMN3mNUNNRphPuGXeQw5vqtOWPvddtOxQ8Z01NOZv6qVFqtY006D-Af728Qdw_73uVJ32tBI_RxaiYO2WYv-BOVGuuWYPec',
                    ]
                ]);
            event(new LoadDataStatusEvent('Dados Carregados!', $this->id));
            $data = (object) json_decode($response->getBody(), true);
            SaveDataJob::dispatch($data, $this->precandidatoId, $this->id, $this->premium);
        }catch (GuzzleException $e) {
            event(new LoadDataStatusEvent('Ocorreu um erro ao buscar os dados em api.programmer.com.br. 
            Favor verificar o log da aplicação para mais detalhes. O job foi colocado na fila novamente com delay de 15 minutos (900 segundos).', $this->id));
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            $this->release(900); //adicionar a fila dnv
        }
    }
	
	private function getHashtags($precandidatoId){
        $hashtags = PreCandidato::find($precandidatoId)->hashtags()->where('primary', true)->get();
        $query = $hashtags[0]->name;
        for($x = 1; $x < count($hashtags); $x++){
            $query.= '%20OR%20'.$hashtags[$x]->name;
        }
		return str_replace('#','%23', $query);
	}
}
