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
use Throwable;
class LoadSentimentsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 1;

    public $timeout = 7200;

    public $tweets;
    public $id;
    private $tweetsAnalizados = 0;
    private $releaseDelay = 240;
    private $remainingAttempts;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($tweets, $id, $remainingAttempts = 3)
    {
        $this->tweets = $tweets;
        $this->id = $id;
        $this->remainingAttempts = $remainingAttempts;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $languageClient = new LanguageClient([
            'keyFilePath' => '/home/vagrant/Code/google_cloud_api/Google_API_Project.json'
        ]);
        $options =  ['language' => 'pt'];
        event(new LoadSentimentsStatusEvent(count($this->tweets) .' tweets foram recebidos e estão sendo análisados.', $this->id));
        try{
            foreach ($this->tweets as $tweet) {
                DB::beginTransaction();
                $sentimentResult = $languageClient->analyzeSentiment($tweet->text, $options);
                $entityResult = $languageClient->analyzeEntities($tweet->text, $options);
                
                $documentSentiment = $sentimentResult->info()['documentSentiment'];

                $sentiment = $this->saveSentiment($documentSentiment['score'], $documentSentiment['magnitude'], $tweet->id);
                $this->saveSentences($sentimentResult->sentences(), $sentiment);
                $this->saveEntities($entityResult->info()['entities'], $sentiment);
                $tweet->sentiment_id = $sentiment->id;
                $tweet->save();
                event(new LoadSentimentsStatusEvent('Tweet de id #'. $tweet->id .' foi analisado e atualizado!', $this->id));
                $this->tweetsAnalizados+=1;
                DB::commit();
            }
            event(new LoadSentimentsStatusEvent($this->tweetsAnalizados.' tweets foram análisados com sucessos!', $this->id));
        } catch(Throwable $e){
            DB::rollBack();
            $this->treatException($e);
        }
    }

    private function treatException(Throwable $e){
        Log::error($e->getMessage());
        Log::error(AppException::getTraceAsString($e));

        if($this->remainingAttempts > 0){
            event(new LoadSentimentsStatusEvent('Ocorreu um erro ao analisar os sentimentos, somente '. $this->tweetsAnalizados .
            ' tweet(s) foram analisados. O job foi posto na fila novamente onde '
            . (count($this->tweets) - $this->tweetsAnalizados) . ' tweet(s) restante(s) serão analisados em: {'. $this->releaseDelay .'} segundos', $this->id));
            $this->tweets = $this->tweets->slice($this->tweetsAnalizados);
            LoadSentimentsJob::dispatch($this->tweets, $this->id, --$this->remainingAttempts)->delay(now()->addSeconds($this->releaseDelay));
        } else {
            event(new LoadSentimentsStatusEvent('O Job falhou pelo máximo número de vezes permitido! Nenhuma nova tentativa será feita.', $this->id));
        }
        $this->fail($e);
    }

    private function saveSentiment($score, $magnitude, $tweetId): Sentiment{
        $sentiment = Sentiment::make($score, $magnitude, $tweetId);
        $sentiment->save();
        return $sentiment;
    }

    private function saveSentences(array $sentences, Sentiment $sentiment){
        foreach ($sentences as $sentenceItem) {
            $sentence = Sentence::make($sentenceItem['text']['content'], $sentenceItem['sentiment']['score'], $sentenceItem['sentiment']['magnitude'], $sentiment->id);
            $sentence->save();
        }
    }

    private function saveEntities(array $entities, Sentiment $sentiment){
        foreach ($entities as $entityItem) {
            $entity = Entity::make($entityItem['name'], $entityItem['type'], $entityItem['metadata'], $entityItem['salience'], $sentiment->id);
            $entity->save();
        }
    }

}
