<?php
namespace App\Interfaces;

use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Interface obrigatória nos models de filtro da aplicação
 *
 * @category Interface
 * @package  App\Interfaces
 * @author   Lucas Reis <lucas@programmer.com.br>
 * @license  https://github.com/lucasctd/tweets-analyzer/blob/master/LICENSE - LICENSE
 * @link     https://github.com/lucasctd/tweets-analyzer
 */
interface FilterInterface
{
    /**
     * Retorna o QueryBuilder das hashtags
     *
     * @see https://laravel.com/docs/5.6/eloquent-relationships#defining-relationships
     *
     * @return HasMany
     */
    public function hashtags() : HasMany;

    /**
     * Retorna o nome da propriedade que representa a primary key
     *
     * @return string
     */
    public function getIdPropertyName() : string;

    /**
     * Retorna o valor para o atributo (JSON) name
     *
     * @return string
     */
    public function getNameAttribute(): string;

    /**
     * Retorna o valor para o atributo (JSON) id
     *
     * @return string
     */
    public function getIdAttribute(): string;
}
