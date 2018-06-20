<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Events\LoadUserDataStatusEvent;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class LoadUsersDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    const usersPerRequest = 100;

    private $users;

    /**
     * Create a new job instance.
     *
     * @param array $users
     */
    public function __construct(array $users)
    {
        $this->users = $users;
    }

    /**
     * Execute the job.
     *
     * @param Client $client
     * @return void
     */
    public function handle(Client $client)
    {
        try{
            $userBlocks = $this->makeUserBlocks($this->users);
            $usersData = array();
            $blockNumber = 1;
            foreach ($userBlocks as $block){
                event(new LoadUserDataStatusEvent('Carregando Dados do bloco de usuários #'.$blockNumber));
                array_push($usersData, $this->getUsersData(implode(',', $block), $client));
                event(new LoadUserDataStatusEvent('Dados do bloco usuários #'.$blockNumber++.' carregados. Número de usuários: '. count($block)));
            }
            event(new LoadUserDataStatusEvent('Os dados de todos os usuários foram carregados.'));
        } catch (GuzzleException $e) {
            event(new LoadUserDataStatusEvent('Ocorreu um erro ao buscar os dados em api.programmer.com.br. Favor verificar o log da aplicação para mais detalhes.'));
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
        }
    }

    /**
     * @param string $users
     * @param Client $client
     * @return object
     * @throws GuzzleException
     */
    private function getUsersData(string $users, Client $client){
        $url = 'https://api.programmer.com.br/Twitter/User/' . $users;
        $response = $client->request('GET', $url,
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjgwYzc3YjJiNTA3MDg0MjZmOThmOTQ0NGUxOTMxZmQ3ZGVkYWFlOWM4OWM1NzlkYWI5ZTFiMDcyYjc1M2FlMjc2MmNhYTVhNWE5ODQ1NGE4In0.eyJhdWQiOiIyMyIsImp0aSI6IjgwYzc3YjJiNTA3MDg0MjZmOThmOTQ0NGUxOTMxZmQ3ZGVkYWFlOWM4OWM1NzlkYWI5ZTFiMDcyYjc1M2FlMjc2MmNhYTVhNWE5ODQ1NGE4IiwiaWF0IjoxNTI2Nzc0NDgxLCJuYmYiOjE1MjY3NzQ0ODEsImV4cCI6MTU1ODMxMDQ4MSwic3ViIjoiMSIsInNjb3BlcyI6W119.L2p_NR0SOsGmiVSDXdMmLKLcQT1u7chNKnl5ZD2TxzF2p1F8gwzmRHO5PNlBIdl1A_agPf6kFXthmHeHYvWtQyd8qADRQjMVwp5DUSbUh1wAKPRBLZsIn7ZahK2lePMz6L8s-t_Pp9Bc5766X6dJrNzTDEsTdMPo0rx8GyzK3oJw6q-4j9C_8BEh0k6clQ0Wq0H5oJN422AWW3C1fOsogO3byFHP8cPshQn5ws4nbF68gofncD2pfX9Em1UxW4WAlCJBt80g_rdIQMm78DDetWGcKPU3YUk5foTpOMnhMs8CjjC7KDDQeHk3i9d-1VljWvaOgLUdEqyYpeq2QhTQhJUVjLbHViymOVCfe8Rx-PEpjaQqVQJ5KZ06xuu2PX_nNe8P8iu3mKjPS5Hn3SQL43lqmZqgiDTdO1peeAkgF0V4hJHNSbs93B_v0XApP2Q7KPtr531zRLuPVv6uwKrywZ2a1pC8Ajaw6_2UKUgaEpXt_bU1iHRMvaB9uIJVyhGc2WBUl6Sipd6L6W-B8EdX8pFiHgaNnPbzq3WCWX94jnwAHXPhWklroa0ECslH9vPK-Jh2qEv3Y27LFFLmyNb5EPOGc_zTTSg7k_xL8Cu5Edi_LpspSzHuezwouHAWQtfU5h0CC1mofLECTo4cdJvozNbz1c5j_d7TuyKlDhLHYow',
                ]
            ]);
        return (object) json_decode($response->getBody(), true);
    }

    /**
     * @param array $users
     * @return array
     */
    private function makeUserBlocks(array $users) : array {
        $blocks = array();
        $numBlocks = ceil(count($users) / self::usersPerRequest);
        for ($x = 0; $x < $numBlocks; $x++){
            array_push($blocks, array_slice($users, $x * self::usersPerRequest, self::usersPerRequest));
        }
        return $blocks;
    }
}
