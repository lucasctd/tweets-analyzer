<?php

namespace App\Jobs;

use App\Events\LoadDataStatusEvent;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
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
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function handle(Client $client)
    {
        $url = 'http://api.programmer.com.br.wazzu/Twitter?query=' . $this->encodeUrl($this->query) . '&count=' . $this->count;
        try{
            event(new LoadDataStatusEvent('Carregando Dados...', $this->id));
            $response = $client->request('GET', $url,
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjY4ZjMyNTcxN2FkNmY1ZTI3ZDhiODkzYTA1N2RjYjE0OGZlMGJmNTJiNWU2ZDRjYWI2OGI1OWY4ZDE3MmNmZDQzNzAwYzM5ZTYwYTdkY2Q4In0.eyJhdWQiOiIxIiwianRpIjoiNjhmMzI1NzE3YWQ2ZjVlMjdkOGI4OTNhMDU3ZGNiMTQ4ZmUwYmY1MmI1ZTZkNGNhYjY4YjU5ZjhkMTcyY2ZkNDM3MDBjMzllNjBhN2RjZDgiLCJpYXQiOjE1MjUzNDg4NTEsIm5iZiI6MTUyNTM0ODg1MSwiZXhwIjoxNTU2ODg0ODUxLCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.cqeWjVyCxPeklfbw4iVvNkVZY065t4MPelJxCwhDfPBBpyn_J86tYvJIumqzO9O-iNER20vf6gRdfJQ7E8J8UfYBy8M7AjbBsbxv_JYcMr6p4soqfSZfdTUWAPOo8ZT0Dz1zhuouuCKNi8Idd_Ryx2uYym4G7Eya6IAHzfqlM7GcSqe_g59jhC3xshgXgIPLug8RbCfi4g-2Ei_YJl4IqhBHI5M84PzyaPmOuVRVliTo9P5eGtyFms8RXCHMeA_KTzguFLfP32lfIKWaWo8sKlgAs8Wtw-BX9otF-43T9wap7ELCvtMrgdOqq_DQsgqpUYIjRkh2fol0dsd7RCpKEHJKkl0sgkAoMCLo8zNwCp-BNccWFz_MwyO0_mtHVurkjFGknazn7aYA5qwJAKZ3h-zkwpoXB-uK-CeGOfjqpNcNaUdots4xOTe4SR5QsXLnoV8Z6DrG_NxT9aFTaTW3gFaMqzch7cXYUNZjrNSQYtuLa8qnfQUZsPhEgv1WRijUi2YFAIrN_pn2u8LHJVxUeCrIylf5FjmIuiVUL_xf9VHmmzIJBrtZzmdKNXogngW66cOMy5zZYfAPdeRnsqRCuQ5DYcrVYscOntlysQV944ySE6a2faU1lobidIy5HVcEZUX42qXw4lnkU0fBwbQY15ZxXKQ612pzoq-VNeOmLX8',
                    ]
                ]);
            event(new LoadDataStatusEvent('Dados Carregados!', $this->id));
            $data = (object) json_decode($response->getBody(), true);
            SaveDataJob::dispatch($data, $this->query, $this->id);
        }catch (RequestException $e) {
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
