<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HashTag extends Model
{
    public $table = 'hashtag';
    public $timestamps = false;

    protected $fillable = [
        'name', 'primary', 'tweet_id'
    ];

    public static function make(string $name, bool $primary, int $tweetId) : HashTag
    {
        $hashtag = new HashTag(
            [
                'name' => $name,
                'primary' => $primary,
                'tweet_id' => $tweetId,
            ]
        );
        return $hashtag;
    }

    /**
     * Get the tweet from what this hashtag belongs to
     */
    public function tweet()
    {
        return $this->belongsTo('App\Models\Tweet', 'tweet_id', 'id');
    }
}
