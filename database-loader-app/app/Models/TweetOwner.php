<?php

namespace App\Models;

use App\Traits\ModelHelper;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TweetOwner extends Model
{
    use ModelHelper;

    public $table = 'tweet_owner';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'id', 'id_str', 'name', 'screen_name', 'location','url','description', 'followers_count', 'friends_count', 'favourites_count', 'statuses_count',
        'user_created_at', 'created_at', 'city_id', 'br_state_id'
    ];

    public static function make(object $data, int $cityId = null, int $stateId = null) : TweetOwner
    {
        $tweetOwner = new TweetOwner(
            [
                'id' => $data->id,
                'id_str' => $data->id_str,
				'name' => $data->name,
                'screen_name' => $data->screen_name,
                'location' => $data->location,
                'url' => $data->url,
                'description' => $data->description,
                'followers_count' => $data->followers_count,
                'friends_count' => $data->friends_count,
                'favourites_count' => $data->favourites_count,
                'statuses_count' => $data->statuses_count,
                //Sat Apr 28 03:18:13 +0000 2018
                'user_created_at' => Carbon::createFromFormat('D M d H:i:s O Y', $data->created_at),
                'created_at' => Carbon::now('America/Bahia'),
                'city_id' => $cityId,
                'br_state_id' => $stateId,
            ]
        );
        return $tweetOwner;
    }

    public function tweets()
    {
        return $this->hasMany('App\Models\Tweet', 'owner_id', 'id');
    }
}
