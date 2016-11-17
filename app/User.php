<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;
use Auth;

class User extends Authenticatable
{
    use EntrustUserTrait;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'account_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function connections()
    {
       return $this->hasMany('App\Connection');
    }

    public function campaigns()
    {
       return $this->hasMany('App\Campaign');
    }

    public function contents()
    {
       return $this->hasMany('App\Content');
    }

    public function tasks()
    {
       return $this->hasMany('App\Task');
    }

    public function account()
    {
        return $this->belongsTo('App\Account');
    }

    public function country()
    {
        return $this->belongsTo('App\Country', 'country_code', 'country_code');
    }

    public function accountConnections()
    {
        return $this->account->connections();
    }

    public function partialWriterAccessOrders()
    {
        return $this->hasMany('App\WriterAccessPartialOrder');
    }

    public function belongsToAgencyAccount()
    {
        // This later will have to be refactored to
        // handle a user being in several different
        // accounts.

        return $this->account->isAgencyAccount();
    }

    public function agencyAccount()
    {
        return $this->account()->where('account_type_id', 2)->first();
    }

    public static function dropdown()
    {
        $authorDropdown = ['' => '-- Select Author --'];
        $authorDropdown += Auth::user()->account
            ->users()
            ->select('name', 'id')
            ->orderBy('name', 'asc')
            ->distinct()
            ->lists('name', 'id')
            ->toArray();

        return $authorDropdown;
    }

    public function connectionsBySlug($slug)
    {
        return $this->connections()
            ->join('providers', '.providers.id', '=', 'connections.provider_id')
            ->where('slug', $slug);
    }

    public function __toString()
    {
        return $this->name;
    }
}
