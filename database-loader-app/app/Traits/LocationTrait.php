<?php

namespace App\Traits;

use App\Models\City;
use App\Models\State;

/**
 * Trait com métodos para busca de dados relativos a localização (Estado e Cidade)
 *
 * @category Trait
 * @package  App\Traits
 * @author   Lucas Reis <lucas@programmer.com.br>
 * @license  https://github.com/lucasctd/tweets-analyzer/blob/master/LICENSE - LICENSE
 * @link     https://github.com/lucasctd/tweets-analyzer
 */
trait LocationTrait
{
    /**
     * Busca pelo código da cidade
     *
     * @param string $cityName - Nome da cidade
     *
     * @return int
     */
    private function _getCity(string $cityName) : ?int
    {
        $city = City::where('nome', $cityName)->get();
        return count($city) === 1 ? $city->first()->codigo : null;
    }

    /**
     * Busca pelo código da cidade
     *
     * @param string $stateName - Nome do Estado
     *
     * @return int
     */
    private function _getState($stateName) : ?int
    {
        $state = State::where('nome', $stateName)->get();
        return $state->isNotEmpty() ? $state->first()->codigo : null;
    }
}
