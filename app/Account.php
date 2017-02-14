<?php

namespace App;

use App\AccountType;
use App\Presenters\AccountPresenter;
use Auth;
use DB;
use Illuminate\Database\Eloquent\Model;
use Laracasts\Presenter\PresentableTrait;

class Account extends Model
{
    use PresentableTrait;

    protected $presenter = AccountPresenter::class;

    public $fillable = [
        'name',
        'account_type_id',
        'parent_account_id',
    ];

    /**
     * Relationships
     */
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

    public function campaigns()
    {
        return $this->hasMany('App\Campaign');
    }

    public function parentAccount()
    {
        return $this->belongsTo('App\Account', 'parent_account_id');
    }

    public function childAccounts()
    {
        return $this->hasMany('App\Account', 'parent_account_id');
    }

    public function personas()
    {
        return $this->hasMany('App\Persona');
    }

    public function buyingStages()
    {
        return $this->hasMany('App\BuyingStage');
    }

    public function tags()
    {
        return $this->hasMany('App\Tag');
    }

    public function tasks()
    {
        return $this->hasMany('App\Task');
    }

    public function ideas()
    {
        return $this->hasMany('App\Idea');
    }

    /**
     * Agency related helper methods
     */
    public function isAgencyAccount()
    {
        return $this->account_type_id == AccountType::AGENCY;
    }

    public static function selectAccount(Account $account)
    {
        Auth::user()->selectedAccount()->associate($account->id);
    }

    public static function selectedAccount()
    {
        if (!Auth::user()) {
            return null;
        }

        $accountId = Auth::user()->selected_account_id;

        if (!$accountId) {
            $account = Auth::user()->accounts[0];
            self::selectAccount($account);
            $accountId = $account->id;
        }

        return self::find($accountId);
    }

    public function connectionsBySlug($slug)
    {
        return $this->connections()
            ->join('providers', '.providers.id', '=', 'connections.provider_id')
            ->where('slug', $slug);
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

    public function cleanContentWithoutStatus()
    {
        $this->contents()
            ->where('written', 0)
            ->where('ready_published', 0)
            ->where('published', 0)
            ->get()
            ->each(function($content) {
                $content->update([ 'written' => 1 ]);
            });
    }
}
