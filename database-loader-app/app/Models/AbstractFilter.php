<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Interfaces\FilterInterface;

/**
 * Classe responsável pelo mapeamento da tabela city
 *
 * @category Model
 * @package  App\Models
 * @author   Lucas Reis <lucas@programmer.com.br>
 * @license  https://github.com/lucasctd/tweets-analyzer/blob/master/LICENSE - LICENSE
 * @link     https://github.com/lucasctd/tweets-analyzer
 */
abstract class AbstractFilter extends Model implements FilterInterface
{
    /**
     * Create a new Eloquent model instance.
     *
     * @param array $attributes -
     *
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        array_push($this->appends, "id", "name");
        array_push($this->visible, "id", "name");
        parent::__construct($attributes);
    }
}
