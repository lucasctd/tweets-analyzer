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


class UpdateOwnersLocationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
        DB::beginTransaction();
        try{
            foreach ($owners as $owner) {
                $location = $this->getLocation($owner->location);
                $city = $this->getCity($location[0]);
                $state = $this->getState($location[0]);
                $cityName = 'NE';
                $stateName = 'NE';
                if($city != null){
                    $owner->city_id = $city->codigo;
                    $cityName = $city->nome;
                }
                if($state != null){
                    $owner->br_state_id = $state->codigo;
                    $stateName = $state->nome;
                }
                if($city != null || $state != null){
                    $owner->save();
                    $totalAtualizado+=1;
                    event(new UpdateOwnersLocationStatusEvent($owner->name . ' teve sua localização atualizada para ' . $cityName . '/' . $stateName));
                }else{
                    $totalNaoAtualizado+=1;
                    event(new UpdateOwnersLocationStatusEvent($owner->name . " não foi atualizado, pois não foi encontrado registro de cidade ou estado para a localização 
                    informada ($location[0])")); 
                }
            }
            event(new UpdateOwnersLocationStatusEvent("Análise finalizadam, $totalAtualizado registros foram atualizados e $totalNaoAtualizado não tiveram sua localização identificada.")); 
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            event(new UpdateOwnersLocationStatusEvent('Ocorreu um erro, alteração foi persistida, favor verificar o log para detalhes.'));
            Log::error($e->getMessage());
            Log::error(AppException::getTraceAsString($e));
        }
        
    }

    private function getCity($cityName) : ?City {
        $city = City::where('nome', $cityName)->get();
        return count($city) === 1 ? $city->first() : null;
    }

    private function getState($cityName) : ?State {
        $state = State::where('nome', $cityName)->get();
        return $state->isNotEmpty() ? $state->first() : null;
    }

    private function getLocation($ownerLocation): array {
        $location = explode(',', $ownerLocation);
        if(count($location) === 1){
            $location = explode('-', $ownerLocation);
        }

        if(count($location) === 1){
            $location = explode('/', $ownerLocation);
        }
        return $location;
    }
}
