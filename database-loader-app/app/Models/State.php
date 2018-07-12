<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Classe resposável pelo mapeamento da tabela state
 *
 * @category Model
 * @package  App\Models
 * @author   Lucas Reis <lucas@programmer.com.br>
 * @license  https://github.com/lucasctd/tweets-analyzer/blob/master/LICENSE - LICENSE
 * @link     https://github.com/lucasctd/tweets-analyzer
 */
class State extends Model
{
    public $table = 'br_state';
    public $timestamps = false;
    public $primaryKey = "codigo";

    protected $fillable = [
        'codigo', 'nome', 'uf'
    ];

    /**
     * Retorna cidades vinculadas ao estado
     *
     * @return Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cities()
    {
        return $this->hasMany('App\Models\City', 'br_state_id', 'codigo');
    }
}
