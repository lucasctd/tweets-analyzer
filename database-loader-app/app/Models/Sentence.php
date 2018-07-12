<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Classe resposável pelo mapeamento da tabela sentence
 *
 * @category Model
 * @package  App\Models
 * @author   Lucas Reis <lucas@programmer.com.br>
 * @license  https://github.com/lucasctd/tweets-analyzer/blob/master/LICENSE - LICENSE
 * @link     https://github.com/lucasctd/tweets-analyzer
 */
class Sentence extends Model
{
    public $table = 'sentence';
    public $timestamps = false;
    public $primaryKey = "id";

    protected $fillable = [
        'text', 'score', 'magnitude', 'sentiment_id'
    ];

    /**
     * Cria uma instância de pre-candidato
     *
     * @param object $text        - texto da sentença
     * @param int    $score       - Resultado da avaliação do Google Natural Language
     * @param string $magnitude   - Magnitude conforme informado pelo Google
     * @param string $sentimentId - Id do sentimento
     *
     * @return Tweet
     */
    public static function make($text, $score, $magnitude, $sentimentId): Sentence
    {
        return new Sentence(
            [
            'text' => $text,
            'score' => $score,
            'magnitude' => $magnitude,
            'sentiment_id' => $sentimentId
            ]
        );
    }

    /**
     * Retorna sentimento da sentença
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sentiment()
    {
        return $this->belongsTo('App\Models\Sentiment', 'id', 'sentiment_id');
    }
}
