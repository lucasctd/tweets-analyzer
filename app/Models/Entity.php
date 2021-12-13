<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Classe responsável pelo mapeamento da tabela entity
 *
 * @category Model
 * @package  App\Models
 * @author   Lucas Reis <lucas@programmer.com.br>
 * @license  https://github.com/lucasctd/tweets-analyzer/blob/master/LICENSE - LICENSE
 * @link     https://github.com/lucasctd/tweets-analyzer
 */
class Entity extends Model
{
    public $table = 'entity';
    public $timestamps = false;
    public $primaryKey = "id";

    protected $fillable = [
        'name', 'type', 'wikipedia_url', 'mid', 'salience','sentiment_id'
    ];

    /**
     * Cria uma instância de Hashtag
     *
     * @param string $name - Nome da hashtag
     * @param string $type - Id do tweet
     * @param array $metadata - Id do filtro
     * @param float $salience - É uma hashtag primária?
     * @param int $sentimentId - É uma hashtag primária?
     *
     * @return Entity
     */
    public static function make(string $name, string $type, array $metadata, float $salience, int $sentimentId): Entity
    {
        $wikipediaUrl = null;
        $mid = null;
        if (array_key_exists('wikipedia_url', $metadata)) {
            $wikipediaUrl = $metadata['wikipedia_url'];
        }

        if (array_key_exists('mid', $metadata)) {
            $mid = $metadata['mid'];
        }
        return new Entity(
            [
                'name' => $name,
                'type'=> $type,
                'wikipedia_url' => $wikipediaUrl,
                'mid' => $mid,
                'salience' => $salience,
                'sentiment_id' => $sentimentId
            ]
        );
    }

    /**
     * Retorna sentimento a qual está entidade pertence
     *
     * @return BelongsTo
     */
    public function sentiment(): BelongsTo
    {
        return $this->belongsTo('App\Models\Sentiment', 'id', 'sentiment_id');
    }
}
