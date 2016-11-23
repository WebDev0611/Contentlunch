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

    public function contents()
    {
        return $this->hasMany('App\Content');
    }

    public function connections()
    {
        return $this->hasMany('App\Connection');
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

    public static function selectAccount(Account $account)
    {
        session([ 'selected_account_id' => $account->id ]);
    }

    public static function selectedAccount()
    {
        $accountId = session('selected_account_id');

        return self::find($accountId);
    }

    public function authorsDropdown()
    {
        $authorDropdown = ['' => '-- Select Author --'];
        $authorDropdown += $this->users()
            ->select('users.name', 'users.id')
            ->orderBy('name', 'asc')
            ->distinct()
            ->lists('name', 'id')
            ->toArray();

        return $authorDropdown;
    }

    public function relatedContentsDropdown()
    {
        // Create Related Drop Down Data
        $dropdown = ['' => '-- Select Related Content --'];
        $dropdown += $this->contents()
            ->select('contents.id','contents.title')
            ->orderBy('title', 'asc')
            ->distinct()
            ->lists('title', 'id')
            ->toArray();

        return $dropdown;
    }
}
