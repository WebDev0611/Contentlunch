<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
    protected $fillable = ['user_id', 'ip_address', 'fingerprint'];

    public static function recentLoginsCount($daysAgo = 7)
    {
        $counts = collect([]);

        for ($i = 0; $i < $daysAgo; $i++) {
            $date = Carbon::now()->subDays($i)->format('m/d/Y');
            $counts[$date] = static::whereBetween('created_at', [
                    Carbon::now()->subDays($i)->startOfDay(),
                    Carbon::now()->subDays($i)->endOfDay(),
                ])->count();
        }

        return $counts;
    }
}
