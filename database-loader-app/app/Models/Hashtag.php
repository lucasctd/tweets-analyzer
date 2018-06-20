<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hashtag extends Model
{
    public $table = 'hashtag';
    public $timestamps = false;

    protected $fillable = [
        'name', 'tweet_id', 'precandidato_id', 'primary'
    ];

    public static function make(string $name, int $tweetId, int $precandidatoId = null, $primary = false) : Hashtag
    {
        $hashtag = new Hashtag
        (
            [
                'name' => $name,
                'tweet_id' => $tweetId,
                'precandidato_id' => $precandidatoId,
                'primary' => $primary,
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

     /**
     * Get the PreCandidato from who this hashtag belongs to
     */
    public function preCandidato()
    {
        return $this->belongsTo('App\Models\PreCandidato', 'precandidato_id', 'id');
    }
}
