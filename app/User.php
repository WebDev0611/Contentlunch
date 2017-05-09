<?php

namespace App;

use App\Account;
use App\AccountType;
use App\Content;
use App\Limit;
use App\Message;
use App\Presenters\UserPresenter;
use Auth;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laracasts\Presenter\PresentableTrait;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use PresentableTrait, Authorizable, EntrustUserTrait {
        Authorizable::can insteadof EntrustUserTrait;
        EntrustUserTrait::can as hasPermission;
    }

    protected $presenter = UserPresenter::class;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    const DEFAULT_PROFILE_IMAGE = '/images/cl-avatar2.png';

    /**
     * User model relationships
     */
    public function accounts()
    {
        return $this->belongsToMany('App\Account');
    }

    public function accountConnections()
    {
        return $this->account->connections();
    }

    public function calendars()
    {
        return $this->hasMany('App\Calendar');
    }

    public function assignedTasks()
    {
        return $this->belongsToMany('App\Task');
    }

    public function campaigns()
    {
       return $this->hasMany('App\Campaign');
    }

    public function connections()
    {
       return $this->hasMany('App\Connection');
    }

    public function country()
    {
        return $this->belongsTo('App\Country', 'country_code', 'country_code');
    }

    public function ideas()
    {
        return $this->hasMany('App\Idea');
    }

    public function limits()
    {
        return $this->belongsToMany('App\Limit')->withTimestamps();
    }

    public function logins()
    {
        return $this->hasMany('App\Login');
    }

    public function partialWriterAccessOrders()
    {
        return $this->hasMany('App\WriterAccessPartialOrder');
    }

    public function selectedAccount()
    {
        return $this->belongsTo('App\Account');
    }

    public function tasks()
    {
       return $this->hasMany('App\Task');
    }

    /**
     * User model custom scopes
     */
    public function scopeCreatedSinceYesterday($query)
    {
        return $query->whereBetween('created_at', [
            Carbon::now()->subDay(),
            Carbon::now(),
        ]);
    }

    public function scopeRecent($query)
    {
        return $query
            ->orderBy('users.created_at', 'desc')
            ->orderBy('users.id', 'desc');
    }

    /**
     * Helper methods
     */
    public function belongsToAgencyAccount()
    {
        return (boolean) $this->accounts()
            ->where('account_type_id', AccountType::AGENCY)
            ->count();
    }

    public function agencyAccount()
    {
        return $this->accounts()
            ->where('account_type_id', AccountType::AGENCY)
            ->first();
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

    public static function search($term, $account = null)
    {
        if (!$account) {
            $account = Account::selectedAccount();
        }

        return $account
            ->users()
            ->where('name', 'like', '%' . $term . '%')
            ->get();
    }

    public function addToLimit($limitName)
    {
        $limit = Limit::whereName($limitName)->first();

        if (!$limit) {
            throw new \Exception("$limitName is not a valid name on the limits table", 1);
        }

        $this->limits()->attach($limit);
    }

    public function isAdmin()
    {
        return $this->is_admin == 1;
    }

    public function isGuest()
    {
        return $this->is_guest == 1;
    }

    public function conversationWith(User $user)
    {
        return Message::orWhere(function($q) use ($user) {
                $q
                    ->where('recipient_id', $user->id)
                    ->where('sender_id', $this->id);
            })
            ->orWhere(function($q) use ($user) {
                $q
                    ->where('recipient_id', $this->id)
                    ->where('sender_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($message) {
                $message->senderData = $message->present()->sender;

                return $message;
            });
    }
}
