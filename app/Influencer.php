<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Influencer extends Model
{
    protected $fillable = [
        'name',
        'description',
        'image_url',
        'twitter_screen_name',
        'twitter_id_str',
        'twitter_followers_count',
    ];

    public function accounts()
    {
        return $this->belongsToMany('App\Account');
    }
}
