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

    protected $fillable = [
        'tweet_id', 'id_str', 'text', 'favorite_count', 'retweet_count','reply_count', 'quote_count','url','tweet_created_at', 'owner_id'
    ];
	
	public static function make(object $data, $ownerId) : Tweet
    {
        $text = self::getValue('text', $data);
        $tweet = new Tweet(
            [
                'tweet_id' => $data->id,
                'id_str' => $data->id_str,
                'text' => $text !== null ? $text : self::getValue('full_text', $data),
                'favorite_count' => $data->favorite_count,
                'retweet_count' => $data->retweet_count,
                'reply_count' => self::getValue('reply_count', $data),
                'quote_count' => self::getValue('quote_count', $data),
                'url' => 'https://twitter.com/tweeter/status/'.$data->id_str,
                //Sat Apr 28 03:18:13 +0000 2018
                'tweet_created_at' => Carbon::createFromFormat('D M d H:i:s O Y', $data->created_at),
            ]
        );
        return $tweet;
    }

    public function owner()
    {
        return $this->belongsTo('App\Models\TweetOwner', 'owner_id', 'id');
    }

}
