<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class Tweet extends Model
{
    public $table = 'tweet';
    public $timestamps = false;

    protected $fillable = [
        'tweet_id', 'id_str', 'text', 'favorite_count', 'retweet_count','quote_count','url','followers_count','tweet_created_at','pla_id', 'tweet_owner'
    ];
	
	public static function make(object $data, ?int $placeId) : Tweet
    {
        $text = self::getValue('text', $data);
        Log::info($data);
        $tweet = new Tweet(
            [
                'tweet_id' => $data->id,
                'id_str' => $data->id_str,
                'text' => $text !== null ? $text : self::getValue('full_text', $data),
                'tweet_owner' => $data->user['screen_name'],
                'favorite_count' => $data->favorite_count,
                'retweet_count' => $data->retweet_count,
                'reply_count' => self::getValue('reply_count', $data),
                'quote_count' => self::getValue('quote_count', $data),
                'url' => 'https://twitter.com/tweeter/status/'.$data->id_str,
                'followers_count' => $data->user['followers_count'],
                //Sat Apr 28 03:18:13 +0000 2018
                'tweet_created_at' => Carbon::createFromFormat('D M d H:i:s O Y', $data->created_at),
                'pla_id' => $placeId,
            ]
        );
        return $tweet;
    }

	/**
	 * Get the place where the tweet was post
	 */
    public function place()
    {
        return $this->belongsTo('App\Models\Place', 'pla_id', 'id');
    }

    /**
     * @param string $property
     * @param $data
     * @return mixed
     */
    public static function getValue(string $property, $data)
    {
        return property_exists($data, $property) ? $data->$property : null;
    }

}
