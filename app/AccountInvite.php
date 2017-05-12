<?php

namespace App;

use App\Helpers;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

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

    public function isGuest()
    {
        return (boolean) $this->is_guest;
    }

    public function generateToken()
    {
        $this->token = str_replace('-', '', Helpers::uuid());
    }

    public function createUser(array $userData)
    {
        $user = User::create([
            'name' => $userData['name'],
            'password' => bcrypt($userData['password']),
            'email' => $userData['email'],
        ]);

        $this->attachUser($user);

        return $user;
    }

    public function attachUser(User $user)
    {
        $user->accounts()->attach($this->account_id);
        $this->user()->associate($user);
        $this->save();
    }
}
