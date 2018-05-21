<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    public $table = 'br_state';
    public $timestamps = false;
    public $primaryKey = "codigo";

    protected $fillable = [
        'codigo', 'nome', 'uf'
    ];

    public function cities()
    {
        return $this->hasMany('App\Models\City', 'br_state_id', 'codigo');
    }
}
