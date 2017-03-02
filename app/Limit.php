<?php

namespace App;

use App\Traits\FindsByName;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Limit extends Model
{
    use FindsByName;

    protected $guarded = [ 'id', 'created_at', 'updated_at' ];
    protected $fillable = ['name', 'display_name', 'value' ];

    const GENERIC_FEEDBACK_MESSAGE = "Looks like you'll need to upgrade to a paid account to proceed with this action.";

    public function subscriptionTypes()
    {
        return $this->belongsToMany(SubscriptionType::class);
    }

    public function scopeMonthly($query)
    {
        return $query->whereBetween('limit_user.created_at', [
            Carbon::now()->subMonth(),
            Carbon::now(),
        ]);
    }

    public static function feedbackMessage($limitName)
    {
        $limit = static::findByName($limitName);

        return $limit ? $limit->feedbackMessage
                      : static::GENERIC_FEEDBACK_MESSAGE;
    }
}
