<?php
namespace App\Models;

use App\Interfaces\FilterInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Classe responsável pelo mapeamento da tabela hashtag
 *
 * @category Model
 * @package  App\Models
 * @author   Lucas Reis <lucas@programmer.com.br>
 * @license  https://github.com/lucasctd/tweets-analyzer/blob/master/LICENSE - LICENSE
 * @link     https://github.com/lucasctd/tweets-analyzer
 */
class Hashtag extends Model
{
    public $table = 'hashtag';
    public $timestamps = false;

    protected $fillable = [
        'name', 'tweet_id', 'filter_id', 'primary'
    ];

    /**
     * Cria uma instância de Hashtag
     *
     * @param string $name - Nome da hashtag
     * @param int $tweetId - Id do tweet
     * @param int|null $filterId - Id do filtro
     * @param bool $primary - É uma hashtag primária?
     *
     * @return Hashtag
     */
    public static function make(string $name, int $tweetId, int $filterId = null, bool $primary = false) : Hashtag
    {
        return new Hashtag(
            [
                'name' => $name,
                'tweet_id' => $tweetId,
                'filter_id' => $filterId,
                'primary' => $primary,
            ]
        );
    }

    /**
     * Get the tweet from what this hashtag belongs to
     *
     * @return BelongsTo
     */
    public function tweet(): BelongsTo
    {
        return $this->belongsTo('App\Models\Tweet', 'tweet_id', 'id');
    }

    /**
     * Get the PreCandidato from who this hashtag belongs to
     *
     * @return BelongsTo
     */
    public function filter(): BelongsTo
    {
        $filter = resolve(FilterInterface::class);
        return $this->belongsTo(get_class($filter), 'filter_id', $filter->getIdPropertyName());
    }
}
