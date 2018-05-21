<?php

namespace App\Jobs;

use App\Models\City;
use App\Models\State;
use App\Models\TweetOwner;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Events\LoadUserDataStatusEvent;
use Exception;
use Illuminate\Support\Facades\DB;

class SaveUserDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;

    /**
     * Create a new job instance.
     *
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            $currentBlock = 1;
            $usuariosInseridos = 0;
            $usuariosRepetidos = 0;
            event(new LoadUserDataStatusEvent('Persistindo dados dos usuários...'));
            DB::transaction(function () use ($currentBlock, $usuariosInseridos, $usuariosRepetidos) {
                foreach ($this->data as $data) {
                    event(new LoadUserDataStatusEvent('Persistindo dados dos usuários do bloco: ' . $currentBlock));
                    foreach ($data as $user) {
                        try{
                            TweetOwner::where('screen_name', $user->screen_name)->firstOrFail();
                            $usuariosRepetidos++;
                        }catch (ModelNotFoundException $e){
                            $cityId = null;
                            $stateId = null;
                            $location = explode(',', $user->location);
                            if(count($location) > 1){
                                $cityId = $this->getCity($location[0]);
                                $stateId = $this->getState($location[0]);
                            }
                            $towner = TweetOwner::make($user, $cityId, $stateId);
                            $towner->save();
                            $usuariosInseridos++;
                        }
                    }
                    event(new LoadUserDataStatusEvent('Dados dos usuários do bloco ' . $currentBlock++ . ' foram persistido com sucesso.'));
                }
            });
            event(new LoadUserDataStatusEvent('Dados de ' . $usuariosInseridos . ' usuários inseridos com sucesso. ' . $usuariosRepetidos++ . ' usuários foram ignorados por repetição.'));
        }catch (Exception $e){

        }
    }

    private function getCity($cityName) : int{
        $city = City::where('nome', $cityName)->get();
        return count($city) === 1 ? $city->first()->codigo : null;
    }

    private function getState($cityName) : int{
        $state = State::where('nome', $cityName)->get();
        return $state->isNotEmpty() ? $state->first()->codigo : null;
    }
}
