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
    public $id;

    /**
     * Create a new job instance.
     *
     * @param $hashtag
     * @param $amount
     * @param $id
     */
    public function __construct($hashtag, $amount, $id)
    {
        $this->hashtag = $hashtag;
        $this->amount = $amount;
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
        $url = 'https://api.programmer.com.br/Twitter?hashtag=' . $this->hashtag . '&amount=' . $this->amount;
        try{
            event(new LoadDataStatusEvent('Carregando Dados...', $this->id));
            $response = $client->request('GET', $url,
                [
                    'headers' => [
                        'Accept' => 'application/json',
                        'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6ImQyNTRkMTJiN2U5OTk2YTFjMGU0MGY2Y2RhZjljYjlmN2Q5NDc1M2E2ZDNjYzUzOGUxN2U5ZWQ4OWQyZGU2ZTEzZTFiNDdmNzBjZjljYTQ4In0.eyJhdWQiOiIxMyIsImp0aSI6ImQyNTRkMTJiN2U5OTk2YTFjMGU0MGY2Y2RhZjljYjlmN2Q5NDc1M2E2ZDNjYzUzOGUxN2U5ZWQ4OWQyZGU2ZTEzZTFiNDdmNzBjZjljYTQ4IiwiaWF0IjoxNTI1MzExMDA3LCJuYmYiOjE1MjUzMTEwMDcsImV4cCI6MTU1Njg0NzAwNywic3ViIjoiMSIsInNjb3BlcyI6W119.Hx8zAUOOZi5LrPcnr8K1ZEyeqXRp5ttNB45Pz5MVb8D18sDr6v9bnE7vjx4G-nqxJIguoIaKh_QcPmx3YiUGnCMm6ftolrXc2YaLKtbi-Tfp-r9WoLeaITe6W2BwP6UEJUy_d78C947XyTYapJZ2EsOHku2fY83Rv2eck9dgt6YpSONER36Aa9DfU2YAl8LXGqCa1C2AOowZW_eR6Zuuw-L1ICObdWpMzRRSRuAVMT7idyP1gJjWark0P3Xvt1UjutOPuJeOLk808VTeavGhWjd2iDfycDKCOD3e8gTTOjaP4xgB5E71I5UssTL1Fu1epxLRmGOrSJR2xsZiFWGiTVtYPYEzvXFNULoDBXH30UU3cUTvTbiZDotBXeCNSNG1PFB7v7jMjxo47l5m8Lia9FSc0K9jh7AWsW87_r1ffmWh7LtDN__h2i8RmRKUA2AqI3G25i757a__gyuBYnmHES7iqfcu_6liLqn0e_gB0Thq8H5z4G-PZF516CNSt8160X5mJoq1lY-REawMPFa2TPS8kosbtyaNvN3D8YmuLxnnxaAd9JLHluVzwcHice59YcTtnqG0BmNLC-NLe1GtzvD34kDUuISUrPoXgxMDIj-erzFe61MdyvsFTSufjPP44ZQNws7uC4GrOul89Rbs_QDy6kUYZo1z0jgqqrIsInY',
                    ]
                ]);
            event(new LoadDataStatusEvent('Dados Carregados!', $this->id));
            $data = (object) json_decode($response->getBody(), true);
            SaveDataJob::dispatch($data, $this->hashtag, $this->id);
        }catch (RequestException $e) {
            event(new LoadDataStatusEvent('Ocorreu um erro ao buscar os dados em api.programmer.com.br. Favor verificar o log da aplicação para mais detalhes.', $this->id));
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
        }
    }
}
