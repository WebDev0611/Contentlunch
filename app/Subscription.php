<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = [
        'account_id',
        'subscription_type_id',
        'start_date',
        'expiration_date',
        'auto_renew',
        'valid',
    ];

    public function subscriptionType()
    {
        return $this->belongsTo('App\subscriptionType');
    }

    public function account()
    {
        return $this->belongsTo('App\Account');
    }
}
