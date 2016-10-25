<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

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

    public static function dropdown()
    {

        // - Create Author Drop Down Data
        //  ---- update sql query to pull ONLY team members once that is added
        $authordd = ['' => '-- Select Author --'];
        $authordd = User::select('id','name')->orderBy('name', 'asc')->distinct()->lists('name', 'id')->toArray();
        return $authordd;
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
