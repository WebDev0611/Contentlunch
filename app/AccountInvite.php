<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class AccountInvite extends Model
{
    public $fillable = [ 'email', 'token', 'account_id' ];

    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function getRouteKeyName()
    {
        return 'token';
    }

    public function isUsed()
    {
        return (boolean) $this->user_id;
    }
}
