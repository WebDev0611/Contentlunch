<?php

namespace App;

use App\Presenters\LoginPresenter;
use App\Traits\Orderable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Laracasts\Presenter\PresentableTrait;

class Login extends Model
{
    use Orderable, PresentableTrait;

    protected $presenter = LoginPresenter::class;

    protected $fillable = ['user_id', 'ip_address', 'user_agent', 'fingerprint'];

    /*
     |--------------------------------------------------------------------------
     | Relationships
     |--------------------------------------------------------------------------
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

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
