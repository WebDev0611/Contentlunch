<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    public $fillable = [ 'name' ];

    public function users()
    {
        return $this->hasMany('App\User');
    }

    public function invites()
    {
        return $this->hasMany('App\AccountInvite');
    }
}
