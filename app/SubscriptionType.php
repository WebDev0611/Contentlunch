<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscriptionType extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'price',
        'price_per_client',
        'limit_users',
        'description',
    ];

    public function subscriptions()
    {
        return $this->hasMany('App\Subscription');
    }
}
