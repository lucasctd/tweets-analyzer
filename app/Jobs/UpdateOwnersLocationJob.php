<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\TweetOwner;
use App\Events\UpdateOwnersLocationStatusEvent;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Exceptions\AppException;
use App\Traits\LocationTrait;
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
class UpdateOwnersLocationJob implements ShouldQueue, JobInterface
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, LocationTrait;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $owners = TweetOwner::all();
        $totalAtualizado = 0;
        $totalNaoAtualizado = 0;
        $total = count($owners);
        $percentual = 0;
        $totalAnalisado = 0;
        DB::beginTransaction();
        try {
            foreach ($owners as $owner) {
                $location = $this->_getLocation($owner->location);
                $city = $this->_getCity($location[0]);
                $state = $this->getState($location[0]);
                $cityName = 'NE';
                $stateName = 'NE';
                if ($city != null) {
                    $owner->city_id = $city->codigo;
                    $cityName = $city->nome;
                }
                if ($state != null) {
                    $owner->br_state_id = $state->codigo;
                    $stateName = $state->nome;
                }
                $novoPercentual = $this->_calcPercentual(++$totalAnalisado, $total);
                if ($novoPercentual > $percentual) {
                    $percentual = $novoPercentual;
                }
                if ($city != null || $state != null) {
                    $owner->save();
                    $totalAtualizado+=1;
                    $this->fireEvent($owner->name . ' teve sua localização atualizada para ' . $cityName . '/' . $stateName .'{'.$percentual.'}');
                } else {
                    $totalNaoAtualizado+=1;
                    $this->fireEvent(
                        $owner->name . " não foi atualizado, pois não foi encontrado registro de cidade ou estado para a localização informada ($location[0])".'{'.$percentual.'}'
                    );
                }
            }
            $this->fireEvent("Análise finalizada, $totalAtualizado registros foram atualizados e $totalNaoAtualizado não tiveram sua localização identificada.");
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->fireEvent('Ocorreu um erro, as alterações não foram persistidas, favor verificar o log para detalhes.');
            Log::error($e->getMessage());
            Log::error(AppException::getTraceAsString($e));
        }
    }

    /**
     * Execute the job.
     *
     * @param string $ownerLocation - Localização do usuário conforme Twitter API
     *
     * @link https://developer.twitter.com/en/docs/tweets/data-dictionary/overview/user-object
     *
     * @return void
     */
    private function _getLocation(string $ownerLocation): array
    {
        $location = explode(',', $ownerLocation);
        if (count($location) === 1) {
            $location = explode('-', $ownerLocation);
        }

        if (count($location) === 1) {
            $location = explode('/', $ownerLocation);
        }
        return $location;
    }

    /**
     * Calcular percentual de $value em relação a $total
     *
     * @param int $value - Autoexplicativo
     * @param int $total - Autoexplicativo
     *
     * @return int - Percentual (inteiro) do valor informado
     */
    private function _calcPercentual(int $value, int $total) : int
    {
        return ($value * 100) / $total;
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
        event(new UpdateOwnersLocationStatusEvent($status));
    }
}
