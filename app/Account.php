<?php

namespace App;

use DB;
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

    public function connections()
    {
        return $this->hasManyThrough('App\Connection', 'App\User');
    }
}
