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

    public function scopeMonthly($query)
    {
        return $query->whereBetween('limit_user.created_at', [
            Carbon::now()->subMonth(),
            Carbon::now(),
        ]);
    }
}
