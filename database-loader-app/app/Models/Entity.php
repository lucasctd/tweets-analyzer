<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    public $table = 'entity';
    public $timestamps = false;
    public $primaryKey = "id";

    protected $fillable = [
        'name', 'type', 'wikipedia_url', 'mid', 'salience','sentiment_id'
    ];

    public static function make($name, $type, $metadata, $salience, $sentimentId): Entity {
        $wikipediaUrl = null;
        $mid = null;
        if(array_key_exists('wikipedia_url', $metadata)){
            $wikipediaUrl = $metadata['wikipedia_url'];
        }

        if(array_key_exists('mid', $metadata)){
            $mid = $metadata['mid'];
        }
        return new Entity([
            'name' => $name, 
            'type'=> $type, 
            'wikipedia_url' => $wikipediaUrl, 
            'mid' => $mid, 
            'salience' => $salience,
            'sentiment_id' => $sentimentId
        ]);
    }

    public function sentiment()
    {
        return $this->belongsTo('App\Models\Sentiment', 'id', 'sentiment_id');
    }
}
