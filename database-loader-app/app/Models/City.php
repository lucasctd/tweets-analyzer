<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public $table = 'city';
    public $timestamps = false;
    public $primaryKey = "codigo";

    protected $fillable = [
        'codigo', 'nome', 'codigo_uf', 'latitude','longitude'
    ];

    public function state()
    {
        return $this->belongsTo('App\Models\State', 'codigo_uf', 'codigo');
    }
}
