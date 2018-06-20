<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Request;
use App\Models\Sentiment;
use App\Models\Sentence;
use App\Models\Entity;
use App\Models\Tweet;
use App\Events\LoadSentimentsStatusEvent;
use Google\Cloud\Language\LanguageClient;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;
use App\Exceptions\AppException;
use Illuminate\Support\Facades\DB;

class LoadSentimentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $languageClient = new LanguageClient([
            'keyFilePath' => '/home/vagrant/Code/tweets-analyzer.wazzu/Google_API_Project.json'
        ]);
        $options =  ['language' => 'pt'];
        $tweets = $this->getTweets();
        $totalTweetsRecebidos = count($tweets);
        event(new LoadSentimentsStatusEvent($totalTweetsRecebidos.' tweets foram encontrados e estão sendo análisados.'));
        $tweetsAnalizados = 0;
        try{
            foreach ($tweets as $tweet) {
                DB::beginTransaction();
                $sentimentResult = $languageClient->analyzeSentiment($tweet->text, $options);
                $entityResult = $languageClient->analyzeEntities($tweet->text, $options);
                
                $documentSentiment = $sentimentResult->info()['documentSentiment'];

                $sentiment = $this->saveSentiment($documentSentiment['score'], $documentSentiment['magnitude'], $tweet->id);
                $this->saveSentences($sentimentResult->sentences(), $sentiment);
                $this->saveEntities($entityResult->info()['entities'], $sentiment);
                $tweet->sentiment_id = $sentiment->id;
                $tweet->save();
                event(new LoadSentimentsStatusEvent('Tweet de id #'. $tweet->id .' foi analisado e atualizado! Total analizado até o momento: '.$tweetsAnalizados));
                $tweetsAnalizados+=1;
                DB::commit();
            }
            event(new LoadSentimentsStatusEvent($tweetsAnalizados.' tweets foram análisados com sucessos!'));
        }catch(Exception $e){
            DB::rollBack();
            event(new LoadSentimentsStatusEvent('Ocorreu um erro ao analisar os sentimentos, somente '. $tweetsAnalizados .
                ' tweet(s) foram analisados. Verifique o log para detalhes.'));
            Log::error($e->getMessage());
            Log::error(AppException::getTraceAsString($e));
        }
    }

    public function getTweets(): Collection {
        return Tweet::doesntHave('sentiment')->take(1000)->get();
    }

    public function saveSentiment($score, $magnitude, $tweetId): Sentiment{
        $sentiment = Sentiment::make($score, $magnitude, $tweetId);
        $sentiment->save();
        return $sentiment;
    }

    public function saveSentences(array $sentences, Sentiment $sentiment){
        foreach ($sentences as $sentenceItem) {
            $sentence = Sentence::make($sentenceItem['text']['content'], $sentenceItem['sentiment']['score'], $sentenceItem['sentiment']['magnitude'], $sentiment->id);
            $sentence->save();
        }
    }

    public function saveEntities(array $entities, Sentiment $sentiment){
        foreach ($entities as $entityItem) {
            $entity = Entity::make($entityItem['name'], $entityItem['type'], $entityItem['metadata'], $entityItem['salience'], $sentiment->id);
            $entity->save();
        }
    }
}
