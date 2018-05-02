<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
	public $table = 'place';
    public $timestamps = false;

    protected $fillable = [
        'name', 'full_name', 'country', 'country_code', 'place_type'
    ];

    public static function make($data) : Place{
        $place = new Place([
           'name' => $data->name,
           'full_name' => $data->full_name,
           'country' => $data->country,
           'country_code' => $data->country_code,
           'place_type' => $data->place_type,
        ]);
        return $place;
    }
	
	/**
     * Get the tweets for the Place.
     */
    public function tweets()
    {
        return $this->hasMany('App\Models\Tweet', 'pla_id', 'id');
    }
}
