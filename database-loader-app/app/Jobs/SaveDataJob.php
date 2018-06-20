<?php

namespace App\Jobs;

use App\Events\LoadDataStatusEvent;
use App\Models\Hashtag;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\City;
use App\Models\State;
use App\Models\TweetOwner;
use App\Models\Tweet;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\DB;
use App\Exceptions\AppException;

class SaveDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    public $precandidatoId;
    public $id;

    /**
     * Create a new job instance.
     *
     * @param $data
     * @param $precandidatoId
     * @param $id
     */
    public function __construct($data, $precandidatoId, $id)
    {
        $this->data = $data;
        $this->precandidatoId = $precandidatoId;
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            event(new LoadDataStatusEvent('Persistindo dados no banco de dados!', $this->id));
            $tweetsDuplicados = 0;
            $tweetsComErro = 0;
            $tweetsSalvos = 0;
			$usuariosRepetidos = 0;
            foreach ($this->data->data as $tweet){
                try{
                    DB::beginTransaction();
                    $tweetJson = (object) $tweet;
                    $user = (object) $tweetJson->user;
                    try{
						$tweetOwner = TweetOwner::where('screen_name', $user->screen_name)->firstOrFail();
						$usuariosRepetidos++;
					}catch (ModelNotFoundException $e){
                        $tweetOwner = $this->saveOwner($user);
					}
                    $tweet = Tweet::make($tweetJson, $tweetOwner->id);
                    $tweet->save();
                    if(isset($tweetJson->entities['hashtags'])){
                        $this->saveHashtags($tweet->id, $tweetJson->entities['hashtags']);
                    }
                    $tweetsSalvos++;
                    DB::commit();
                }catch (Exception $e){
                    DB::rollBack();
                    $tweetText = Tweet::getValue('text', $tweetJson) != null ? Tweet::getValue('text', $tweetJson) : Tweet::getValue('full_text', $tweetJson);
                    if($e->getCode() == 23000){
                        $tweetsDuplicados++;
                    }else{
                        $tweetsComErro++;
                        Log::error($e->getMessage());
                        Log::error(AppException::getTraceAsString($e));
                    }
                }
            }
            event(new LoadDataStatusEvent('Dados persistidos no banco de dados! Tweets salvos: '.$tweetsSalvos.'. Tweets duplicados: '.$tweetsDuplicados.'. Tweets com erro: '.$tweetsComErro, $this->id));
        }catch (Exception $e){
            event(new LoadDataStatusEvent('Ocorreu um erro ao persistir os dados. Favor verificar o log da aplicação para mais detalhes. '. $e->getMessage(), $this->id));
            Log::error($e->getMessage());
            Log::error(AppException::getTraceAsString($e));
        }
    }

    private function saveHashtags(int $tweetId, array $hashTags){
        foreach ($hashTags as $hashTag){
            $hashTag = Hashtag::make('#' . $hashTag['text'], $tweetId, $this->precandidatoId);
            $hashTag->save();
        }
    }
	
	private function saveOwner($user) : TweetOwner{
		$cityId = null;
		$stateId = null;
		$location = explode(',', $user->location);
        $cityId = $this->getCity($location[0]);
        $stateId = $this->getState($location[0]);
		$towner = TweetOwner::make($user, $cityId, $stateId);
		$towner->save();
		return $towner;
	}
	
	private function getCity($cityName) : ?int{
        $city = City::where('nome', $cityName)->get();
        return count($city) === 1 ? $city->first()->codigo : null;
    }

    private function getState($cityName) : ?int{
        $state = State::where('nome', $cityName)->get();
        return $state->isNotEmpty() ? $state->first()->codigo : null;
    }

    /**
     * The job failed to process.
     *
     * @param Exception $e
     * @return void
     */
    public function failed(Exception $e)
    {
        event(new LoadDataStatusEvent('Ocorreu um erro ao executar o job.', $this->id));
        Log::error($e->getMessage());
        Log::error($e->getTraceAsString());
    }
}
