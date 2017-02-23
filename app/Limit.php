<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Limit extends Model
{
    protected $guarded = [ 'id', 'created_at', 'updated_at' ];

    public function scopeMonthly($query)
    {
        return $query->whereBetween('limit_user.created_at', [
            Carbon::now()->subMonth(),
            Carbon::now(),
        ]);
    }
}
