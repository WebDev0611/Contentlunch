<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class Login extends Model
{
    protected $fillable = ['user_id', 'ip_address', 'user_agent', 'fingerprint'];

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

    public static function registerLogin(Request $request, User $user)
    {
        return static::create([
            'user_id' => $user->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->header('User-Agent'),
            'fingerprint' => $request->fingerprint(),
        ]);
    }
}
