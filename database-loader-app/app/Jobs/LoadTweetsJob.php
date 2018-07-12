<?php
namespace App\Jobs;

use App\Events\LoadTweetsStatusEvent;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Interfaces\FilterInterface;
use App\Interfaces\JobInterface;

/**
 * Classe resposável pela busca dos Tweets
 *
 * @category Job
 * @package  App\Jobs
 * @author   Lucas Reis <lucas@programmer.com.br>
 * @license  https://github.com/lucasctd/tweets-analyzer/blob/master/LICENSE - LICENSE
 * @link     https://github.com/lucasctd/tweets-analyzer
 */
class LoadTweetsJob implements ShouldQueue, JobInterface
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $premium;
    public $count;
    public $filterId;
    public $fromDate;
    public $toDate;
    public $id;

    public $timeout = 300;

    /**
     * Create a new job instance.
     *
     * @param bool   $premium  - Informa se requisição vai acessar api premium do Twitter
     * @param number $count    - Quantidade de Tweets
     * @param string $filterId - Id do filtro
     * @param Date   $fromDate -Data inicial
     * @param Date   $toDate   - Data final
     * @param number $id       - Id do Job
     */
    public function __construct($premium, $count, $filterId, $fromDate, $toDate, $id)
    {
        $this->premium = $premium;
        $this->count = $count;
        $this->filterId = $filterId;
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @link https://developer.twitter.com/en/docs/tweets/search/api-reference/get-search-tweets
     * @link https://developer.twitter.com/en/docs/tweets/search/api-reference/premium-search.html
     *
     * @return void
     */
    public function handle()
    {
        try {
            $this->fireEvent('Carregando Dados...');
            if ($this->premium) {
                $tweets = $this->_loadTweetsPremiumAPI($this->count, $this->toDate, $this->fromDate, $this->filterId);
            } else {
                $tweets = $this->_loadTweetsStandartAPI($this->count, $this->toDate, $this->filterId);
            }
            $this->fireEvent('Dados Carregados!');
            SaveTweetsJob::dispatch($tweets, $this->filterId, $this->id);
        } catch (GuzzleException $e) {
            $this->fireEvent(
                'API do Twitter está exausta. O job foi colocado na fila novamente com delay de 15 minutos ({900} segundos).'
            );
            $this->release(900); //adicionar a fila novamente após 15 minutos
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            $this->fireEvent(
                'Ocorreu um erro ao buscar os dados em api.programmer.com.br. 
                Favor verificar o log da aplicação para mais detalhes.'
            );
            $this->fail();
        }
    }

    /**
     * Buscar os tweets usando a API Standart do Twitter
     *
     * @param int $count    - Quantidade de tweets buscados
     * @param int $until    - Data limite
     * @param int $filterId - Id do filtro
     *
     * @return array
     */
    private function _loadTweetsStandartAPI(int $count, string $until, int $filterId) : array
    {
        $url = 'http://api.programmer.com.br.wazzu/Twitter?query=' . $this->_getHashtags($filterId) . '&count=' . $count . '&until=' . $until;
        //.'&XDEBUG_SESSION_START=vscode';
        return $this->_makeRequest($url);
    }

    /**
     * Buscar os tweets usando a API Premium do Twitter
     *
     * @param int $count    - Quantidade de tweets buscados
     * @param int $toDate   - Data limite
     * @param int $fromDate - Data limite
     * @param int $filterId - Id do filtro
     *
     * @return array
     */
    private function _loadTweetsPremiumAPI(int $count, string $toDate, string $fromDate, int $filterId) : array
    {
        $url = 'http://api.programmer.com.br.wazzu/TwitterPremium30?query=' . $this->_getHashtags($filterId) . '&count=' . $count
                    .'&fromDate='. $fromDate.'&toDate='.$toDate; //.'&XDEBUG_SESSION_START=vscode';
        return $this->_makeRequest($url);
    }

    /**
     * Buscar os tweets da API do Twitter
     *
     * @param string $url - Url a ser requsitada
     *
     * @return array
     *
     * @throws GuzzleException
     */
    private function _makeRequest(string $url) : array
    {
        $client = resolve(Client::class);
        $response = $client->request(
            'GET',
            $url,
            [
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjU3OGFmNzJmODM2YjgzODA4N2YzZGMxMjA4YTBjY2Y4ZjVhNzczYjM4MmQ5NjFlNjhmMzRmZjgwMjc0N2FiYTY4Y2RmNGNmZWJhMDY4YWI1In0.eyJhdWQiOiIxIiwianRpIjoiNTc4YWY3MmY4MzZiODM4MDg3ZjNkYzEyMDhhMGNjZjhmNWE3NzNiMzgyZDk2MWU2OGYzNGZmODAyNzQ3YWJhNjhjZGY0Y2ZlYmEwNjhhYjUiLCJpYXQiOjE1Mjk0NzU5NjcsIm5iZiI6MTUyOTQ3NTk2NywiZXhwIjoxNTYxMDExOTY3LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.EGT7dTwbALLOtxIfyHXx80AaTA3hFGhfe3N_Rti18KkmwOiMUx0_V7OAexqJ4tR9eL37TjsAnzVYNq-v_IG7OTlcYFXYl-V7oiGsm817ucy1Ny_2iNZGAuX5x3cbyCAVWs97rAYguBAF-IlxmUrL0TMK0KOT7gZwFWDcv6GSTjT1KRAr-nEHdi7fvKLgzBp5yHadkIop-KwJ6b6H0-HoxHlss2i3MU1c6RoviUUKtkGhddMa-qal_afMjCdUmAk6rjVPpaaoizxkrE1I024oToWkEhnq46ASQsT3CbPzHNlaXXT9-emO_HD1f9io-fSOM8b_MTni8MHvgeH9_daYsyLKejLTsVLSBG_I8mel6qeHoHj8TG-YGggFGqHv8eC7-KHlacp6nL6QODa7SGbU4zMLiHS8yEPbrJQALn_tZCsuQEpSIuBivM0-pJkxcZgJqHdlh3dM3vg7U28k8bwJtM1-eQ5lMOFDKZWQCRWQmzYmbJZL_ezAIY7L2jP9cY2Th4HltRYJ7Tqr1CJk4sdiWRLOhro0j7akTlIM-hoDFS1DAG0wOS3oMwZHmohWYpnpwhUAXddamL2jdMN3mNUNNRphPuGXeQw5vqtOWPvddtOxQ8Z01NOZv6qVFqtY006D-Af728Qdw_73uVJ32tBI_RxaiYO2WYv-BOVGuuWYPec',
                ]
            ]
        );
        return ((object) json_decode($response->getBody(), true))->data;
    }
    
    /**
     * Buscar hashtags por filtro
     *
     * @param int $filterId - Id do filtro
     *
     * @return string
     */
    private function _getHashtags($filterId)
    {
        $hashtags = resolve(FilterInterface::class)->find($filterId)->hashtags()->where('primary', true)->get();
        $query = $hashtags[0]->name;
        for ($x = 1; $x < count($hashtags); $x++) {
            $query.= '%20OR%20'.$hashtags[$x]->name;
        }
        return str_replace('#', '%23', $query);
    }

    /**
     * Dispara o evento de status do Job
     *
     * @param string $status - Status do Job
     *
     * @return void
     */
    public function fireEvent(string $status) : void
    {
        event(new LoadTweetsStatusEvent($status, $this->id));
    }
}
