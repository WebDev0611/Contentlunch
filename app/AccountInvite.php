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

    public function getRouteKeyName()
    {
        return 'token';
    }

    public function isUsed()
    {
        return (boolean) User::where('email', $this->email)->count();
    }
}
