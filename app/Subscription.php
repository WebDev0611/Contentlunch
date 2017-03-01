<?php

namespace App;

use Carbon\Carbon;
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
        return $this->belongsTo(SubscriptionType::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function scopeActive($query)
    {
        return $query->where('valid', '=', 1)
            ->where(function($q) {
               $q->where('start_date', '<=', Carbon::now())
                 ->where('expiration_date', '>=', Carbon::now());
            })
            ->orWhere(function($q) {
                $q->where('start_date', '<=', Carbon::now())
                  ->where('expiration_date', '0000-00-00');
            });
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('updated_at', 'desc');
    }
}