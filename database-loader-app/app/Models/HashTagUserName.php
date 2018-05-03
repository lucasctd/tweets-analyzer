<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HashTagUserName extends Model
{
    public $table = 'hashtag_username';
    public $timestamps = false;

    protected $fillable = [
        'name', 'primary', 'tweet_id', 'username'
    ];

    public static function make(string $name, bool $primary, bool $username, int $tweetId) : HashTag
    {
        $hashTagUserName = new HashTag(
            [
                'name' => $name,
                'primary' => $primary,
				'username' => $username,
                'tweet_id' => $tweetId,
            ]
        );
        return $hashTagUserName;
    }

    /**
     * Get the tweet from what this hashtag belongs to
     */
    public function tweet()
    {
        return $this->belongsTo('App\Models\Tweet', 'tweet_id', 'id');
    }
}
