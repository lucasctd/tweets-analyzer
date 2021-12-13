<?php

namespace App\Traits;

/**
 * Trait com métodos de ajuda para os models
 *
 * @category Trait
 * @package  App\Traits
 * @author   Lucas Reis <lucas@programmer.com.br>
 * @license  https://github.com/lucasctd/tweets-analyzer/blob/master/LICENSE - LICENSE
 * @link     https://github.com/lucasctd/tweets-analyzer
 */
trait ModelTrait
{
    /**
     * Retorna o valor da propriedade caso a mesma exista
     *
     * @param string $property - Propriedade buscada
     * @param object $data     - Objeto a ser verificado
     *
     * @return mixed
     */
    public static function getValue(string $property, object $data): mixed
    {
        return property_exists($data, $property) ? $data->$property : null;
    }
}
