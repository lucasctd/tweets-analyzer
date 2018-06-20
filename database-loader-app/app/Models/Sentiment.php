<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sentiment extends Model
{
    public $table = 'sentiment';
    public $timestamps = false;
    public $primaryKey = "id";

    protected $fillable = [
        'score', 'magnitude'
    ];

    public static function make($score, $magnitude, $tweetId): Sentiment {
        return new Sentiment([
            'score' => $score,
            'magnitude' => $magnitude,
        ]);
    }
}
