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
     * @param float $score     - score do tweet conforme GNL
     * @param float $magnitude - magnitude do sentimento conforme GNL
     *
     * @return Sentiment
     */
    public static function make(float $score, float $magnitude): Sentiment
    {
        return new Sentiment(
            [
                'score' => $score,
                'magnitude' => $magnitude,
            ]
        );
    }
}
