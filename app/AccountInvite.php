<?php

namespace App;

use App\Helpers;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;

class AccountInvite extends Model
{
    public $fillable = [ 'email', 'account_id', 'is_guest' ];

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

    public function inviteable()
    {
        return $this->morphTo();
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

    public function scopeAvailable($query)
    {
        return $query->where('user_id', null);
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

    public function createGuest(array $userData)
    {
        $user = User::create([
            'name' => $userData['name'],
            'password' => bcrypt($userData['password']),
            'email' => $userData['email'],
            'is_guest' => true,
        ]);

        $this->attachUser($user);

        return $user;
    }

    public function attachUser(User $user)
    {
        if (!$this->account->users()->find($user->id)) {
            $user->accounts()->attach($this->account_id);
        }

        switch ($this->inviteable_type) {
            case Content::class: $user->guestContents()->attach($this->inviteable); break;
            default:
        }

        $this->user()->associate($user);
        $this->save();
    }

    public static function findByGuestToken($token)
    {
        return self::where('is_guest', true)
            ->where('token', $token)
            ->available()
            ->first();
    }

    public static function findByUserToken($token)
    {
        return self::where('is_guest', false)
            ->where('token', $token)
            ->available()
            ->first();
    }
}
