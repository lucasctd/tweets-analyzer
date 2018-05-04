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
        $url = 'https://api.programmer.com.br/Twitter?query=' . $this->encodeUrl($this->query) . '&count=' . $this->count;
        try{
            event(new LoadDataStatusEvent('Carregando Dados...', $this->id));
            $response = $client->request('GET', $url,
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjZlNDY0YzczM2E3MDVmNTk3ZWY4NzJmYWI0NDFiMTE5Y2M0NzVlMjQ0NTAwNDhhOWQ4NzE5NmNkZGFjNjI0OTM4YmNlZWJkOTkzYTUxNDRiIn0.eyJhdWQiOiIxNyIsImp0aSI6IjZlNDY0YzczM2E3MDVmNTk3ZWY4NzJmYWI0NDFiMTE5Y2M0NzVlMjQ0NTAwNDhhOWQ4NzE5NmNkZGFjNjI0OTM4YmNlZWJkOTkzYTUxNDRiIiwiaWF0IjoxNTI1MzkzMjQ2LCJuYmYiOjE1MjUzOTMyNDYsImV4cCI6MTU1NjkyOTI0Niwic3ViIjoiMSIsInNjb3BlcyI6W119.HlssUad-gyTY5XnDKDJSlYCTuFEPA6tjKGUlvRIP2rC6-klfbSRCBPVEEr8pfQdQeI9RddVcQBe6CXvZLgC7BI9erDAMOpfFq_aofM7WQoYZxTHhIQy3_QdeRZT_RTepfgKOzJHtEETvoQdaVW1eDdAW8VPmAQLBJ2kwtEdHqsW1wG3Qohg44-JKm1lVUWmopG3-P6eVkmsvU3MFoZ7udihO4paQRFAiubxJ-ygz6cR3nozPDDhXMH-eL8X2_jiKXQm1jkOBnbcYNWW9JYiPEfH286LVeqhWdErk-toMJ05NH-sVlwE0MmcXpIRPTU1gbhIRuOPjdHWrhuTU3Qb4n-eiJcphAcEXxF6ILBBABf4UCXcOPZbny9QtTrpNN0PV1udFh0W4N6xyQ-54SM5EYxVQbRAZWqVfNr5h5BOF4t4MMp-jCRwl6vaE3fdAo_s1tWRKklMH6rcgqY4HKqbZV453J0U6me5QjQFAgcRXhkzipvlU_xriH7u4JSF9oqlRiIe0Xm0NAFxxIkl8nTKO5RcWW2wv5sM-ivFCB2IKRWy8iAPi2UAhwZezdhRBPMRa9HTzN7r1hWlIkGN9s48Hg7XmQSfXJfZm3_5ixAV-7tjGiBkKl-NVa0bOAWhLm_sHXnPIHs4O3C7NiiGUxMfRJ6Vx5qGPjdziXcJeu9ASU8Y',
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
