<?php

namespace App\Jobs;

use App\Events\LoadDataStatusEvent;
use App\Models\HashTag;
use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Place;
use App\Models\Tweet;
use Illuminate\Support\Facades\Log;

class SaveDataJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;
    public $hashtag;

    /**
     * Create a new job instance.
     *
     * @param $data
     * @param $hashtag
     */
    public function __construct($data, $hashtag)
    {
        $this->data = $data;
        $this->hashtag = $hashtag;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try{
            event(new LoadDataStatusEvent('Persistindo dados no banco de dados!'));
            foreach ($this->data->data as $tweet){
                $tweetJson = (object) $tweet;
                $placeId = $this->getPlace($tweetJson);
                $tweet = Tweet::make($tweetJson, $placeId);
                $tweet->save();
                $this->saveHashTag($tweet->id);
                if(isset($tweetJson->entities['hashtags'])){
                    $this->saveSecudaryHashTags($tweet->id, $tweetJson->entities['hashtags']);
                }
                event(new LoadDataStatusEvent('Dados persistidos no banco de dados!'));
            }
        }catch (\Exception $e){
            event(new LoadDataStatusEvent('Ocorreu um erro ao persistir os dados. Favor verificar o log da aplicação para mais detalhes.'));
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
        }
    }

    private function getPlace($tweet) : ?int{
        if(isset($tweet->place)){
            try{
                $place = Place::where('full_name', $tweet->place['full_name'])->firstOrFail();
            }catch (ModelNotFoundException $e){
                $place = Place::make((object) $tweet->place);
                $place->save();
            }
            return $place->id;
        }
        return null;
    }

    private function saveHashTag(int $tweetId){
        $hashTag = HashTag::make($this->hashtag, true, $tweetId);
        $hashTag->save();
    }

    private function saveSecudaryHashTags(int $tweetId, array $hashTags){
        foreach ($hashTags as $hashTag){
            $hashTag = HashTag::make($hashTag['text'], false, $tweetId);
            $hashTag->save();
        }
    }

    /**
     * The job failed to process.
     *
     * @param \Exception $e
     * @return void
     */
    public function failed(\Exception $e)
    {
        event(new LoadDataStatusEvent('Ocorreu um erro ao executar o job.'));
        Log::error($e->getMessage());
        Log::error($e->getTraceAsString());
    }
}
