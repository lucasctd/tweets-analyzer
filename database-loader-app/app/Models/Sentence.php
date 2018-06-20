<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sentence extends Model
{
    public $table = 'sentence';
    public $timestamps = false;
    public $primaryKey = "id";

    protected $fillable = [
        'text', 'score', 'magnitude', 'sentiment_id'
    ];

    public static function make($text, $score, $magnitude, $sentimentId): Sentence {
        return new Sentence([
            'text' => $text, 
            'score' => $score, 
            'magnitude' => $magnitude, 
            'sentiment_id' => $sentimentId
        ]);
    }

    public function sentiment()
    {
        return $this->belongsTo('App\Models\Sentiment', 'id', 'sentiment_id');
    }
}
