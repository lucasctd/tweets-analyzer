<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Classe resposável pelo mapeamento da tabela precandidato
 *
 * @category Model
 * @package  App\Models
 * @author   Lucas Reis <lucas@programmer.com.br>
 * @license  https://github.com/lucasctd/tweets-analyzer/blob/master/LICENSE - LICENSE
 * @link     https://github.com/lucasctd/tweets-analyzer
 */
class PreCandidato extends AbstractFilter
{
    public $table = 'precandidato';
    public $timestamps = false;

    protected $fillable = [
        'nome', 'partido'
    ];

    /**
     * Cria uma instância de pre-candidato
     *
     * @param string $nome    - Nome do pre-candidato
     * @param string $partido - Partido do pre-candidato
     *
     * @return PreCandidato
     */
    public static function make(string $nome, string $partido) : PreCandidato
    {
        return new PreCandidato(
            [
                'nome' => $nome,
                'partido' => $partido,
            ]
        );
    }

    /**
     * Retorna lista de hashtags vinculadas ao pre-candidato
     *
     * @return HasMany
     */
    public function hashtags() : HasMany
    {
        return $this->hasMany('App\Models\Hashtag', 'filter_id', 'id');
    }

    /**
     * Retorna lista de tweets vinculados ao pre-candidato
     *
     * @return HasMany
     */
    public function tweets(): HasMany
    {
        return $this->hasMany('App\Models\Tweet', 'filter_id', 'id');
    }

    /**
     *
     * @inheritDoc
     */
    public function getIdPropertyName() : string
    {
        return "id";
    }

    /**
     *
     * @inheritDoc
     */
    public function getNameAttribute(): string
    {
        return $this->attributes['nome'];
    }

    /**
     *
     * @inheritDoc
     */
    public function getIdAttribute(): string
    {
        return $this->attributes['id'];
    }
}
