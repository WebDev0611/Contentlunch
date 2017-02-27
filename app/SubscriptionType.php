<?php

namespace App;

use App\Helpers;
use App\Limit;
use App\Traits\FindsBySlug;
use Illuminate\Database\Eloquent\Model;

class SubscriptionType extends Model
{
    use FindsBySlug;

    protected $fillable = [
        'name',
        'slug',
        'price',
        'price_per_client',
        'limit_users',
        'description',
    ];

    public static function boot()
    {
        parent::boot();
        static::saving(function($type) {
            $type->slug = Helpers::slugify($type->name);
        });
    }

    public function limits()
    {
        return $this->belongsToMany('App\Limit')->withPivot('value');
    }

    public function subscriptions()
    {
        return $this->hasMany('App\Subscription');
    }

    public function addLimit(Limit $limit, $value = 0)
    {
        return $this->limits()->attach($limit, [ 'value' => $value ]);
    }
}
