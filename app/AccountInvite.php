<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Helpers;

class AccountInvite extends Model
{
    public $fillable = [ 'email', 'account_id' ];

    public static function boot()
    {
        parent::boot();
        static::creating(function($invite) {
            $invite->generateToken();
        });
    }

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

    public function generateToken()
    {
        $this->token = str_replace('-', '', Helpers::uuid());
    }

    public function createUser(array $userData)
    {
        $user = User::create($userData);
        $this->user()->associate($user);
        $user->accounts()->attach($this->account_id);
    }
}
