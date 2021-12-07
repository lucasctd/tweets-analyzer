<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Classe responsável pelo mapeamento da tabela city
 *
 * @category Model
 * @package  App\Models
 * @author   Lucas Reis <lucas@programmer.com.br>
 * @license  https://github.com/lucasctd/tweets-analyzer/blob/master/LICENSE - LICENSE
 * @link     https://github.com/lucasctd/tweets-analyzer
 */
class City extends Model
{
    public $table = 'city';
    public $timestamps = false;
    public $primaryKey = "codigo";

    protected $fillable = [
        'codigo', 'nome', 'codigo_uf', 'latitude','longitude'
    ];

    /**
     * Retorna o estado a qual esta cidade pertence
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function state()
    {
        return $this->belongsTo('App\Models\State', 'codigo_uf', 'codigo');
    }
}
