<?php

namespace App\Jobs;

use App\Events\LoadSentimentsStatusEvent;
use App\Exceptions\AppException;
use App\Interfaces\JobInterface;
use App\Models\Entity;
use App\Models\Sentence;
use App\Models\Sentiment;
use Google\Cloud\Language\LanguageClient;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

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

    public int $tries = 1;
    public int $timeout = 7200;
    private int $tweetsAnalizados = 0;
    private int $releaseDelay = 240;

    /**
     * Create a new job instance.
     *
     * @param Collection $tweets             - Lista de tweets a serem analisados
     * @param int        $id                 - Id do job
     * @param int        $remainingAttempts - Quantidades de tentativas restantes
     *
     * @return void
     */
    public function __construct(public Collection $tweets, public int $id, private int $remainingAttempts = 3)
    {
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

                $sentiment = $this->saveSentiment($documentSentiment['score'], $documentSentiment['magnitude'], $tweet->id);
                $this->_saveSentences($sentimentResult->sentences(), $sentiment);
                $this->_saveEntities($entityResult->info()['entities'], $sentiment);
                $tweet->sentiment_id = $sentiment->id;
                $tweet->save();
                $this->tweetsAnalizados+=1;
                $novoPercentual = $this->_calcPercentual($this->tweetsAnalizados, $total);
                if ($novoPercentual != $percentual) {
                    $percentual = $novoPercentual;
                    $this->fireEvent('{'.$percentual.'}%');
                }
                DB::commit();
            }
            $this->fireEvent($this->tweetsAnalizados.' tweets foram análisados com sucessos!');
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

        if ($this->remainingAttempts > 0) {
            $this->fireEvent(
                'Ocorreu um erro ao analisar os sentimentos, somente '. $this->tweetsAnalizados .
                ' tweet(s) foram analisados. O job foi posto na fila novamente onde '
                .(count($this->tweets) - $this->tweetsAnalizados) . ' tweet(s) restante(s) serão analisados em: {'. $this->releaseDelay .'} segundos'
            );
            $this->tweets = $this->tweets->slice($this->tweetsAnalizados);
            LoadSentimentsJob::dispatch($this->tweets, $this->id, --$this->remainingAttempts)->delay(now()->addSeconds($this->releaseDelay));
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
     * @param int $tweetId   - Exceção
     *
     * @return Sentiment
     *@link https://cloud.google.com/natural-language/docs/basics
     *
     */
    private function saveSentiment(float $score, float $magnitude, int $tweetId): Sentiment
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
