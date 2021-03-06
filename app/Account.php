<?php

namespace App;

use App\AccountType;
use App\Presenters\AccountPresenter;
use App\Traits\Orderable;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use Laracasts\Presenter\PresentableTrait;

class Account extends Model
{
    use PresentableTrait, Orderable;

    protected $presenter = AccountPresenter::class;

    public $fillable = [
        'name',
        'account_type_id',
        'parent_account_id',
    ];

    const DEFAULT_ACCOUNT_IMAGE = '/images/account-icon-1.png';

    /**
     * Relationships.
     */
    public function users()
    {
        //return $this->belongsToMany('App\User', 'account_user', 'account_id', 'user_id');
        return $this->hasOne('App\User');
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

    public function guidelines()
    {
        return $this->hasOne('App\Guideline');
    }

    public function parentAccount()
    {
        return $this->belongsTo('App\Account', 'parent_account_id');
    }

    public function childAccounts()
    {
        return $this->hasMany('App\Account', 'parent_account_id');
    }

    public function influencers()
    {
        return $this->belongsToMany('App\Influencer');
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

    public function type()
    {
        return $this->belongsTo('App\AccountType', 'account_type_id');
    }

    public function ideas()
    {
        return $this->hasMany('App\Idea');
    }

    public function subscriptions()
    {
        return $this->hasMany('App\Subscription');
    }

    public function activeChildAccounts()
    {
        return $this->childAccounts()->whereEnabled(true);
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

    public function activeSubscriptionType()
    {
        return $this->activeSubscription()->subscriptionType;
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

    public function activeChildSubscriptions()
    {
        return $this->subscriptions()
            ->with('SubscriptionType')
            ->active()
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

    /**
     * @return Account|null
     */
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

    public function getActiveConnections ()
    {
        return $this->connections()
            ->select('id','name', 'provider_id')
            ->active()
            ->with('provider')
            ->get();
    }

    public function authorsDropdown()
    {
        $authorDropdown = ['' => '-- Select Author --'];
        $authorDropdown += $this->users()
            ->select('users.name', 'users.id')
            ->orderBy('name', 'asc')
            ->distinct()
            ->pluck('name', 'id')
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
            ->pluck('title', 'id')
            ->toArray();

        return $dropdown;
    }

    public function calendarsDropdown()
    {
        return $this->calendars()
            ->pluck('name', 'id')
            ->toArray();
    }

    public function cleanContentWithoutStatus()
    {
        $this->contents()
            ->where('content_status_id', 0)
            ->get()
            ->each(function ($content) {
                $content->update(['content_status_id' => 1]);
            });
    }

    public function subscribe(SubscriptionType $subscriptionType, $attributes = [], $proxyToParent = true)
    {
        $account = $proxyToParent ? $this->proxyToParent() : $this;
        $account->deactivateOldSubscriptions($proxyToParent);
        $account->sendPlanChangeEmail($subscriptionType);

        return $account->createSubscription($subscriptionType, $attributes);
    }

    public function subscribeWithoutEmail(SubscriptionType $subscriptionType, $attributes = [], $proxyToParent = true)
    {
        $account = $proxyToParent ? $this->proxyToParent() : $this;
        $account->deactivateOldSubscriptions($proxyToParent);

        return $account->createSubscription($subscriptionType, $attributes);
    }

    public function deactivateOldSubscriptions($proxyToParent = true)
    {
        $account = $proxyToParent ? $this->proxyToParent() : $this;

        return $account->subscriptions()->update([ 'valid' => 0 ]);
    }

    protected function createSubscription(SubscriptionType $subscriptionType, $attributes)
    {
        $default = [
            'start_date' => Carbon::now(),
            'auto_renew' => 0,
            'valid' => 1,
            'subscription_type_id' => $subscriptionType->id,
            'expiration_date' => '0000-00-00',
        ];

        return $this->subscriptions()->create(array_merge($default, $attributes));
    }

    protected function sendPlanChangeEmail(SubscriptionType $subscriptionType)
    {
        $mailData = [
            'oldPlanName' => $this->oldSubscriptionType()->name,
            'newPlanName' => $subscriptionType->name
        ];

        $emails = $this->users()->pluck('email')->toArray();
        Mail::send('emails.new_subscription', $mailData, function($message) use ($emails) {
            $message->from("no-reply@contentlaunch.com", "Content Launch")
                ->to($emails)
                ->subject('Subscription Plan Change');
        });
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

    public function oldSubscriptionType($proxyToParent = true)
    {
        $account = $proxyToParent ? $this->proxyToParent() : $this;

        $subscription = $account->subscriptions()->latest()->first();
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
            $account->subscribeWithoutEmail(SubscriptionType::findBySlug('free'));
        }
    }

    public function getUsers($proxyToParent = true)
    {
        return $proxyToParent ? $this->proxyToParent()->users()->get() : $this->users()->get();
    }

    public function bookmarkInfluencer(array $data)
    {
        $influencer = Influencer::where('twitter_id_str', $data['twitter_id_str'])->first();

        if ($influencer) {
            $influencer->update($data);
            $this->influencers()->attach($influencer);
        } else {
            $influencer = $this->influencers()->create($data);
        }

        return $influencer;
    }

    public function unbookmarkInfluencer(Influencer $influencer)
    {
        return $this->influencers()->detach($influencer);
    }

    public function guestList()
    {
        return User::select('users.*')
            ->join('content_guest', 'content_guest.user_id', '=', 'users.id')
            ->join('contents', 'contents.id', '=', 'content_guest.content_id')
            ->where('contents.account_id', $this->id)
            ->groupBy('users.id')
            ->get()
            ->map(function($user) {
                $user->profile_image = $user->present()->profile_image;

                return $user;
            });
    }
}
