<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Classe resposável pelo mapeamento da tabela sentiment
 *
 * @category Model
 * @package  App\Models
 * @author   Lucas Reis <lucas@programmer.com.br>
 * @license  https://github.com/lucasctd/tweets-analyzer/blob/master/LICENSE - LICENSE
 * @link     https://github.com/lucasctd/tweets-analyzer
 */
class Sentiment extends Model
{
    public $table = 'sentiment';
    public $timestamps = false;
    public $primaryKey = "id";

    protected $fillable = [
        'score', 'magnitude'
    ];

    /**
     * Cria uma instância de Sentiment
     *
     * @param object $score     - score do tweet conforme GNL
     * @param int    $magnitude - magnitude do sentimento conforme GNL
     * @param string $tweetId   - Id do Tweet
     *
     * @return Sentiment
     */
    public static function make($score, $magnitude, $tweetId): Sentiment
    {
        return new Sentiment(
            [
                'score' => $score,
                'magnitude' => $magnitude,
            ]
        );
    }
}
