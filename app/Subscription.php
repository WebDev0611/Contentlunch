<?php

namespace App;

use App\Presenters\SubscriptionPresenter;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

class Subscription extends Model
{
    use PresentableTrait;

    protected $presenter = SubscriptionPresenter::class;

    protected $fillable = [
        'account_id',
        'subscription_type_id',
        'start_date',
        'expiration_date',
        'auto_renew',
        'valid',
        'stripe_subscription_id'
    ];

    public function subscriptionType()
    {
        return $this->belongsTo(SubscriptionType::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function isPaid ()
    {
        return $this->subscriptionType->slug != 'free' && $this->subscriptionType->slug != 'trial';
    }

    public function scopeActive($query)
    {
        return $query->where(function($q) {
                $q->where('valid', '=', 1)
                    ->where('start_date', '<=', Carbon::now())
                    ->where('expiration_date', '>=', Carbon::now());
            })
            ->orWhere(function($q) {
                $q->where('valid', '=', 1)
                    ->where('start_date', '<=', Carbon::now())
                    ->where('expiration_date', '0000-00-00');
            });
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopePaid($query)
    {
        return $query->whereHas('subscriptionType', function ($q) {
            $q->where('slug', '<>', 'free')
                ->where('slug', '<>', 'trial');
        });
    }
}
