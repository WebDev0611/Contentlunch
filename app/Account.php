<?php

namespace App;

use DB;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    public $fillable = [
        'name',
        'account_type_id',
        'parent_account_id',
    ];

    public function users()
    {
        return $this->belongsToMany('App\User', 'account_user', 'account_id', 'user_id');
    }

    public function invites()
    {
        return $this->hasMany('App\AccountInvite');
    }

    public function connections()
    {
        return $this->hasManyThrough('App\Connection', 'App\User');
    }

    public function parentAccount()
    {
        return $this->belongsTo('App\Account', 'parent_account_id');
    }

    public function childAccounts()
    {
        return $this->hasMany('App\Account', 'parent_account_id');
    }

    public function isAgencyAccount()
    {
        return $this->account_type_id == 2;
    }
}
