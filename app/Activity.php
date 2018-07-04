<?php

namespace App;

use App\Account;
use App\Presenters\ActivityPresenter;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

class Activity extends \Spatie\Activitylog\Models\Activity
{
    use PresentableTrait;

    protected $presenter = ActivityPresenter::class;

    public static function boot()
    {
        parent::boot();
        static::creating(function($activity) {
            if ($selectedAccount = Account::selectedAccount()) {
                $activity->account_id = $selectedAccount->id;
            }
        });
    }
}
