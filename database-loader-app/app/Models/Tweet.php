<?php

namespace App\Models;

use App\Traits\ModelHelper;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    use ModelHelper;

    public $table = 'tweet';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id', 'id_str', 'text', 'favorite_count', 'retweet_count','reply_count', 'quote_count','url','tweet_created_at', 'owner_id', 'sentiment_id', 'precandidato_id'
    ];
	
	public static function make(object $data, $ownerId, $premium, $precandidatoId) : Tweet
    {
        
        $tweet = new Tweet(
            [
                'id' => $data->id,
                'id_str' => $data->id_str,
                'text' => self::getText($data, $premium),
                'favorite_count' => $data->favorite_count,
                'retweet_count' => $data->retweet_count,
                'reply_count' => self::getValue('reply_count', $data),
                'quote_count' => self::getValue('quote_count', $data),
                'followers_count' => self::getValue('followers_count', $data),
                'url' => 'https://twitter.com/tweeter/status/'.$data->id_str,
                'owner_id' => $ownerId,
                'precandidato_id' => $precandidatoId,
                //Sat Apr 28 03:18:13 +0000 2018
                'tweet_created_at' => Carbon::createFromFormat('D M d H:i:s O Y', $data->created_at),
            ]
        );
        return $tweet;
    }

    private static function getText(object $data, $premium){
        if(property_exists($data, 'retweeted_status')){
            return self::getText(((object) $data->retweeted_status), $premium);
        }
        if($premium === '1'){
            if(property_exists($data, 'extended_tweet')){
                $text = self::getValue('full_text', ((object) $data->extended_tweet));
            }else{
                $text = self::getValue('text', $data);
            }
            return $text;
        }else{
            $text = self::getValue('text', $data);
            return $text !== null ? $text : self::getValue('full_text', $data);
        }
    }

    public function owner()
    {
        return $this->belongsTo('App\Models\TweetOwner', 'owner_id', 'id');
    }

    public function precandidato()
    {
        return $this->belongsTo('App\Models\PreCandidato', 'precandidato_id', 'id');
    }

    public function sentiment(){
        return $this->hasOne('App\Models\Sentiment', 'id', 'sentiment_id');
    }

}
