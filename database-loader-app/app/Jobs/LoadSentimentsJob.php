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
use App\Interfaces\JobInterface;

/**
 * Classe resposável carregar e salvar os tweets
 *
 * @category Job
 * @package  App\Jobs
 * @author   Lucas Reis <lucas@programmer.com.br>
 * @license  https://github.com/lucasctd/tweets-analyzer/blob/master/LICENSE - LICENSE
 * @link     https://github.com/lucasctd/tweets-analyzer
 */
class LoadSentimentsJob implements ShouldQueue, JobInterface
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;
    public $timeout = 7200;
    public $tweets;
    public $id;
    private $_tweetsAnalizados = 0;
    private $_releaseDelay = 240;
    private $_remainingAttempts;

    /**
     * Create a new job instance.
     *
     * @param Collection $tweets             - Lista de tweets a serem analisados
     * @param int        $id                 - Id do job
     * @param int        $_remainingAttempts - Quantidades de tentativas restantes
     *
     * @return void
     */
    public function __construct(Collection $tweets, int $id, int $_remainingAttempts = 3)
    {
        $this->tweets = $tweets;
        $this->id = $id;
        $this->_remainingAttempts = $_remainingAttempts;
    }

    /**
     * Execute the job.
     *
     * @link https://cloud.google.com/natural-language/docs/quickstart-client-libraries
     *
     * @return void
     */
    public function handle()
    {
        $languageClient = new LanguageClient(
            [
                'keyFilePath' => '/home/vagrant/Code/google_cloud_api/Google_API_Project.json'
            ]
        );
        $options =  ['language' => 'pt'];
        $total = count($this->tweets);
        $percentual = 0;
        $this->fireEvent($total .' tweets foram recebidos e estão sendo análisados.');
        try {
            foreach ($this->tweets as $tweet) {
                DB::beginTransaction();
                $sentimentResult = $languageClient->analyzeSentiment($tweet->text, $options);
                $entityResult = $languageClient->analyzeEntities($tweet->text, $options);
                
                $documentSentiment = $sentimentResult->info()['documentSentiment'];

                $sentiment = $this->_saveSentiment($documentSentiment['score'], $documentSentiment['magnitude'], $tweet->id);
                $this->_saveSentences($sentimentResult->sentences(), $sentiment);
                $this->_saveEntities($entityResult->info()['entities'], $sentiment);
                $tweet->sentiment_id = $sentiment->id;
                $tweet->save();
                $this->_tweetsAnalizados+=1;
                $novoPercentual = $this->_calcPercentual($this->_tweetsAnalizados, $total);
                if ($novoPercentual != $percentual) {
                    $percentual = $novoPercentual;
                    $this->fireEvent('{'.$percentual.'}%');
                }
                DB::commit();
            }
            $this->fireEvent($this->_tweetsAnalizados.' tweets foram análisados com sucessos!');
        } catch (Throwable $e) {
            DB::rollBack();
            $this->_treatException($e);
        }
    }

    /**
     * Trata exceção do job
     *
     * @param Throwable $e - Exceção
     *
     * @return void
     */
    private function _treatException(Throwable $e)
    {
        Log::error($e->getMessage());
        Log::error(AppException::getTraceAsString($e));

        if ($this->_remainingAttempts > 0) {
            $this->fireEvent(
                'Ocorreu um erro ao analisar os sentimentos, somente '. $this->_tweetsAnalizados .
                ' tweet(s) foram analisados. O job foi posto na fila novamente onde '
                .(count($this->tweets) - $this->_tweetsAnalizados) . ' tweet(s) restante(s) serão analisados em: {'. $this->_releaseDelay .'} segundos'
            );
            $this->tweets = $this->tweets->slice($this->_tweetsAnalizados);
            LoadSentimentsJob::dispatch($this->tweets, $this->id, --$this->_remainingAttempts)->delay(now()->addSeconds($this->_releaseDelay));
        } else {
            $this->fireEvent('O Job falhou pelo máximo número de vezes permitido! Nenhuma nova tentativa será feita.');
        }
        $this->fail($e);
    }

    /**
     * Persiste sentimento do tweet
     *
     * @param float $score     - Score do tweet conforme GNL
     * @param float $magnitude - Magnitude do tweet conforme GNL
     * @param int   $tweetId   - Exceção
     *
     * @link https://cloud.google.com/natural-language/docs/basics
     *
     * @return Sentiment
     */
    private function _saveSentiment($score, $magnitude, $tweetId): Sentiment
    {
        $sentiment = Sentiment::make($score, $magnitude, $tweetId);
        $sentiment->save();
        return $sentiment;
    }

    /**
     * Persiste sentenças do tweet
     *
     * @param array     $sentences - Sentenças do tweet conforme GNL
     * @param Sentiment $sentiment - Sentimento do tweet conforme GNL
     *
     * @link https://cloud.google.com/natural-language/docs/basics
     *
     * @return void
     */
    private function _saveSentences(array $sentences, Sentiment $sentiment)
    {
        foreach ($sentences as $sentenceItem) {
            $sentence = Sentence::make($sentenceItem['text']['content'], $sentenceItem['sentiment']['score'], $sentenceItem['sentiment']['magnitude'], $sentiment->id);
            $sentence->save();
        }
    }

    /**
     * Persiste entidades do tweet conforme
     *
     * @param array     $entities  - Entidades do tweet conforme GNL
     * @param Sentiment $sentiment - Sentimento do tweet conforme GNL
     *
     * @link https://cloud.google.com/natural-language/docs/basics
     *
     * @return void
     */
    private function _saveEntities(array $entities, Sentiment $sentiment)
    {
        foreach ($entities as $entityItem) {
            $entity = Entity::make($entityItem['name'], $entityItem['type'], $entityItem['metadata'], $entityItem['salience'], $sentiment->id);
            $entity->save();
        }
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
     * Dispara evento relacionado ao Job
     *
     * @param string $status - Status do Job
     *
     * @return void
     */
    public function fireEvent(string $status) : void
    {
        event(new LoadSentimentsStatusEvent($status, $this->id));
    }
}
