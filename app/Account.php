<?php

namespace App;

use App\AccountType;
use App\Presenters\AccountPresenter;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
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

    const DEFAULT_ACCOUNT_IMAGE = '/images/logo-client-fake.jpg';

    /**
     * Relationships.
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

    public function calendars()
    {
        return $this->hasMany('App\Calendar');
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

    public function subscriptions()
    {
        return $this->hasMany('App\Subscription');
    }

    // Returns all active subscriptions for the account
    public function activeSubscriptions()
    {
        $acc = $this->parentAccount == null ? $this : $this->parentAccount;
        return $acc->subscriptions()
            ->with('SubscriptionType')
            ->active()
            ->latest()
            ->get();
    }

    public function activeSubscription()
    {
        return $this->activeSubscriptions()->first();
    }

    public function activePaidSubscriptions() {
        $acc = $this->parentAccount == null ? $this : $this->parentAccount;
        return $acc->subscriptions()
            ->with('SubscriptionType')
            ->active()
            ->paid()
            ->latest()
            ->get();
    }

    /**
     * Checks if a user belongs to an account.
     *
     * @param User $user
     * @return bool
     */
    public function hasUser(User $user)
    {
        return (boolean) $this->proxyToParent()->users()->whereUserId($user->id)->count();
    }

    /**
     * Agency related helper methods.
     */
    public function isAgencyAccount()
    {
        return $this->account_type_id == AccountType::AGENCY;
    }

    public function isSubAccount() {
        return $this->parentAccount != null;
    }

    public static function selectAccount(Account $account)
    {
        Auth::user()->selectedAccount()->associate($account->id)->save();
        $account->ensureAccountHasSubscription();
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
            ->select('contents.id', 'contents.title')
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
            ->each(function ($content) {
                $content->update(['written' => 1]);
            });
    }

    public function subscribe(SubscriptionType $subscriptionType, $attributes = [], $proxyToParent = true)
    {
        $mailData = [
            'oldPlanName' => $this->subscriptionType()->name,
            'newPlanName' => $subscriptionType->name
        ];

        $account = $proxyToParent ? $this->proxyToParent() : $this;
        $account->deactivateOldSubscriptions($proxyToParent);

        $default = [
            'start_date' => Carbon::now(),
            'auto_renew' => 0,
            'valid' => 1,
            'subscription_type_id' => $subscriptionType->id,
        ];

        // Notice all users on the account about subscription change
        $emails = $account->users()->pluck('email')->toArray();
        Mail::send('emails.new_subscription', $mailData, function($message) use ($emails) {
            $message->from("no-reply@contentlaunch.com", "Content Launch")
                ->to($emails)
                ->subject('Subscription Plan Change');
        });

        return $account->subscriptions()->create(array_merge($default, $attributes));
    }

    public function startTrial()
    {
        if ($this->activeSubscriptions()->isEmpty()) {
            $attributes = [ 'expiration_date' => Carbon::now()->addDays(14) ];
            $trialPlan = SubscriptionType::findBySlug('trial');

            $this->subscribe($trialPlan, $attributes, false);
        }
    }

    public function subscriptionType($proxyToParent = true)
    {
        $account = $proxyToParent ? $this->proxyToParent() : $this;

        $subscription = $account->subscriptions()->active()->latest()->first();
        $free = SubscriptionType::findBySlug('free');

        $subType = $subscription
            ? $subscription->subscriptionType
            : $free;

        return $subType ?: $free;
    }

    public function proxyToParent()
    {
        return $this->parentAccount ?: $this;
    }

    public function limit($limitName, $proxyToParent = true)
    {
        $account = $proxyToParent ? $this->proxyToParent() : $this;

        return $account->subscriptionType()->limit($limitName);
    }

    public function hasLimit($limitName, $proxyToParent = true)
    {
        $account = $proxyToParent ? $this->proxyToParent() : $this;

        return $account->subscriptionType()->hasLimit($limitName);
    }

    public function ensureAccountHasSubscription($proxyToParent = true)
    {
        $account = $proxyToParent ? $this->proxyToParent() : $this;

        if ($account->activeSubscriptions()->isEmpty()) {
            $account->subscribe(SubscriptionType::findBySlug('free'));
        }
    }

    public function deactivateOldSubscriptions($proxyToParent = true)
    {
        $account = $proxyToParent ? $this->proxyToParent() : $this;

        return $account->subscriptions()->update([ 'valid' => 0 ]);
    }

    public function getUsers($proxyToParent = true)
    {
        return $this->proxyToParent()->users()->get();
    }
}
