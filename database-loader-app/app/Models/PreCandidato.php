<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PreCandidato extends Model
{
    public $table = 'precandidato';
    public $timestamps = false;

    protected $fillable = [
        'nome', 'partido'
    ];

    public static function make(string $nome, string $partido) : PreCandidato
    {
        $preCandidato = new PreCandidato
        (
            [
                'nome' => $nome,
                'partido' => $partido,
            ]
        );
        return $preCandidato;
    }

    public function hashtags(){
        return $this->hasMany('App\Models\Hashtag', 'precandidato_id', 'id');
    }

    public function tweets(){
        return $this->hasMany('App\Models\Tweet', 'precandidato_id', 'id');
    }
}
