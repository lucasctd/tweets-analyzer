<?php

namespace App\Models;

use App\Interfaces\FilterInterface;
use App\Traits\ModelTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Classe resposável pelo mapeamento da tabela tweet
 *
 * @category Model
 * @package  App\Models
 * @author   Lucas Reis <lucas@programmer.com.br>
 * @license  https://github.com/lucasctd/tweets-analyzer/blob/master/LICENSE - LICENSE
 * @link     https://github.com/lucasctd/tweets-analyzer
 */
class Tweet extends Model
{
    use ModelTrait;

    public $table = 'tweet';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id', 'id_str', 'text', 'favorite_count', 'retweet_count','reply_count', 'quote_count','url','tweet_created_at', 'owner_id', 'sentiment_id', 'filter_id'
    ];
    
    /**
     * Cria uma instância de Tweet
     *
     * @param object $tweet    - objeto tweet
     * @param int    $ownerId  - id do dono do tweet
     * @param string $filterId - Id do filtro
     *
     * @return Tweet
     */
    public static function make(object $tweet, int $ownerId, int $filterId) : Tweet
    {
        
        $tweet = new Tweet(
            [
                'id' => $tweet->id,
                'id_str' => $tweet->id_str,
                'text' => self::_getText($tweet),
                'favorite_count' => $tweet->favorite_count,
                'retweet_count' => $tweet->retweet_count,
                'reply_count' => self::getValue('reply_count', $tweet),
                'quote_count' => self::getValue('quote_count', $tweet),
                'followers_count' => self::getValue('followers_count', $tweet),
                'url' => 'https://twitter.com/tweeter/status/'.$tweet->id_str,
                'owner_id' => $ownerId,
                'filter_id' => $filterId,
                //Sat Apr 28 03:18:13 +0000 2018
                'tweet_created_at' => Carbon::createFromFormat('D M d H:i:s O Y', $tweet->created_at),
            ]
        );
        return $tweet;
    }

    /**
     * Retorna o texto do tweet independete do mesmo ser premium ou não. Se retweet, busca o texto do tweet original
     *
     * @param object $tweet - Tweet
     *
     * @return string
     */
    private static function _getText(object $tweet) : string
    {
        if (property_exists($tweet, 'retweeted_status')) {
            return self::_getText(((object) $tweet->retweeted_status));
        }
        if (property_exists($tweet, 'extended_tweet')) {//premium api
            $text = self::getValue('full_text', ((object) $tweet->extended_tweet));
        } else {
            $text = self::getValue('text', $tweet);
            $text = $text !== null ? $text : self::getValue('full_text', $tweet);//standart api
        }
        return $text;
    }

    /**
     * Retorna dono do Tweet
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo('App\Models\TweetOwner', 'owner_id', 'id');
    }

    /**
     * Retorna entidade de filtro do Tweet
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function filter()
    {
        $filter = resolve(FilterInterface::class);
        return $this->belongsTo(get_class($filter), 'filter_id', $filter->getIdPropertyName());
    }

    /**
     * Retorna sentimento do Tweet
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sentiment()
    {
        return $this->hasOne('App\Models\Sentiment', 'id', 'sentiment_id');
    }
}
