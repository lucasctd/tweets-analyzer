<?php
/**
 * Classe resposável por receber as requisições da aplicação
 * PHP Version 7.2
 *
 * @category Job
 * @package  App\Jobs
 * @author   Lucas Reis <lucas@programmer.com.br>
 * @license  https://github.com/lucasctd/tweets-analyzer/blob/master/LICENSE - LICENSE
 * @link     https://github.com/lucasctd/tweets-analyzer
 */
namespace App\Jobs;

use App\Events\LoadTweetsStatusEvent;
use App\Exceptions\AppException;
use App\Interfaces\JobInterface;
use App\Models\Hashtag;
use App\Models\Tweet;
use App\Models\TweetOwner;
use App\Traits\LocationTrait;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Classe resposável por persistir os tweets
 *
 * @category Job
 * @package  App\Jobs
 * @author   Lucas Reis <lucas@programmer.com.br>
 * @license  https://github.com/lucasctd/tweets-analyzer/blob/master/LICENSE - LICENSE
 * @link     https://github.com/lucasctd/tweets-analyzer
 */
class SaveTweetsJob implements ShouldQueue, JobInterface
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, LocationTrait;

    public $tweets;
    public $filterId;
    public $id;

    /**
     * Create a new job instance.
     *
     * @param array $tweets   - Lista de tweets retornado da api do Twitter
     * @param int   $filterId - id do filtro
     * @param int   $id       - id do job
     */
    public function __construct(array $tweets, int $filterId, int $id)
    {
        $this->tweets = $tweets;
        $this->filterId = $filterId;
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->fireEvent('Persistindo dados no banco de dados!');
        $tweetsDuplicados = 0;
        $tweetsComErro = 0;
        $tweetsSalvos = 0;
        $usuariosRepetidos = 0;
        foreach ($this->tweets as $tweet) {
            try {
                DB::beginTransaction();
                $tweetJson = (object) $tweet;
                $user = (object) $tweetJson->user;
                try {
                    $tweetOwner = TweetOwner::where('screen_name', $user->screen_name)->firstOrFail();
                    $usuariosRepetidos++;
                } catch (ModelNotFoundException $e) {
                    $tweetOwner = $this->_saveOwner($user);
                }
                $tweet = Tweet::make($tweetJson, $tweetOwner->id, $this->filterId);
                $tweet->save();
                $this->_saveHashtags($tweet->id, $this->_getHashtagsList($tweetJson));
                $tweetsSalvos++;
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                if ($e->getCode() == 23000) { //https://dev.mysql.com/doc/refman/5.5/en/error-messages-server.html#error_er_dup_key
                    $tweetsDuplicados++;
                } else {
                    $tweetsComErro++;
                    Log::error($e->getMessage());
                    Log::error(AppException::getTraceAsString($e));
                }
            }
        }
        $this->fireEvent('Dados persistidos no banco de dados! Tweets salvos: '.$tweetsSalvos.'. Tweets duplicados: '.$tweetsDuplicados.'. Tweets com erro: '.$tweetsComErro.'. {done}');
    }

    /**
     * Retorna lista com as hashtags de encontradas no tweet
     *
     * @param object $data - Objeto do tweet
     *
     * @return array
     */
    private function _getHashtagsList(object $data) : array
    {
        if (property_exists($data, 'retweeted_status')) {
            return $this->_getHashtagsList(((object) $data->retweeted_status));
        }
        if (property_exists($data, 'extended_tweet')) {
            $extendedTweet = ((object) $data->extended_tweet);
            $entities = $extendedTweet->entities;
        } else {
            $entities = $data->entities;
        }
        if (isset($entities['hashtags'])) {
            return $entities['hashtags'];
        }
        return [];
    }

    /**
     * Persiste as hashtags encontradas no tweet
     *
     * @param int   $tweetId  - Id do tweet
     * @param array $hashTags - lista de hashtags a serem persistidas
     *
     * @return void
     */
    private function _saveHashtags(int $tweetId, array $hashTags)
    {
        foreach ($hashTags as $hashTag) {
            $hashTag = Hashtag::make('#' . $hashTag['text'], $tweetId, $this->filterId);
            $hashTag->save();
        }
    }
    
    /**
     * Persiste os dados do usuário do Twitter
     *
     * @param object $user - Usuário a ser persistido
     *
     * @return TweetOwner
     */
    private function _saveOwner(object $user) : TweetOwner
    {
        $cityId = null;
        $stateId = null;
        $location = explode(',', $user->location);
        $cityId = $this->_getCity($location[0]);
        $stateId = $this->_getState($location[0]);
        $towner = TweetOwner::make($user, $cityId, $stateId);
        $towner->save();
        return $towner;
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
