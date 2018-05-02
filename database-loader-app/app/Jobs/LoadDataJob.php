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

    public $amount;
    public $hashtag;

    /**
     * Create a new job instance.
     *
     * @param $amount
     * @param $hashtag
     */
    public function __construct($hashtag, $amount)
    {
        $this->hashtag = $hashtag;
        $this->amount = $amount;
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
        $url = 'https://api.programmer.com.br/Twitter?hashtag=' . $this->hashtag . '&amount=' . $this->amount;
        try{
            event(new LoadDataStatusEvent('Carregando Dados...'));
            $response = $client->request('GET', $url,
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjVhZDk0MjM1OWVlMTZmYWE5MjY5OGVhNzg5NTY4OWQ4M2VhNzNjNjg3YzE3NmZhMDQzYWRjYmEwZDIxNDEwMDAyODY5ZjdjMTZiMjVkMDVkIn0.eyJhdWQiOiIxMSIsImp0aSI6IjVhZDk0MjM1OWVlMTZmYWE5MjY5OGVhNzg5NTY4OWQ4M2VhNzNjNjg3YzE3NmZhMDQzYWRjYmEwZDIxNDEwMDAyODY5ZjdjMTZiMjVkMDVkIiwiaWF0IjoxNTI0Nzg5Nzc4LCJuYmYiOjE1MjQ3ODk3NzgsImV4cCI6MTU1NjMyNTc3OCwic3ViIjoiMSIsInNjb3BlcyI6W119.Mq6BhnB8gPs05QhynhohGdaeRbZnUzDoMCCSmg4YWiphA6UYEBG1Ozwlj9jIhfhZfe_p3cR0H2AyPdD24l93M6cMPhA_xD3mtx_d1ePg1k9xsZaa5XyJUJ8UzeGkki4bDoRisfjbSsMIhblfo1s_sTTwhQOGckhwKJVd5YqMEx4d222_JUEeMf8xwaQ1pphlFSDIXF-Yf6nfY1fcuIkXQfM6GddboYXlV0QYGD-i2kGbx4G1wQgLmWs-LKbu9yTufda4FTVZMsZypJ9BqOOl4bSWpedB5bmK7s-iuKTPxsaB3tu8NSYxP7TS6zTlZ9ZvzdHQ_UbMtUDLXjT43CYjB4OLJipD0NLmaNWybVO_aYlJMpC7FhV2J-t9EIO3DR7fgmn7uy7LYDZd0NnYqtkuAJqAbseAPFv8ydlQBBPE-u9ntQuJfBVvB-GbgVMCqrE7xSfhPTdFfln2H0gxHV-6VWk_sIE_mnX7jR5ijAUoB1zynsD_KDaGFPz2I_rLO3gyMiXZFA76xwNpP2fd93XocN2ZhPBLjYYyEEM2c79Fo46-NJ7yEpWyucv6TNJnL0RyMm0mP5F6o8-czOOAR0-sRGqunF8b57UrOwlswlaKqfw0MqL1iO97ywQJ3hto8-QmZrQ5JohUzDeZuY-kiEIsnQsLz4AfxbFFLPq1MAIh9dc',
                    ]
                ]);
            event(new LoadDataStatusEvent('Dados Carregados!'));
            $data = (object) json_decode($response->getBody(), true);
            SaveDataJob::dispatch($data, $this->hashtag);
        }catch (RequestException $e) {
            event(new LoadDataStatusEvent('Ocorreu um erro ao buscar os dados em api.programmer.com.br. Favor verificar o log da aplicação para mais detalhes.'));
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
        }
    }
}
