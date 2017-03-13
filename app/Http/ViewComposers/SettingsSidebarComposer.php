<?php

namespace App\Http\ViewComposers;

use App\Account;
use App\User;
use Auth;
use Config;
use Log;
use Stripe\Customer;
use Stripe\Error\Base;
use Stripe\Stripe;
use Illuminate\View\View;

class SettingsSidebarComposer
{
    protected $user;
    protected $account;

    /**
     * SettingsSidebarComposer constructor.
     */
    public function __construct()
    {
        $this->user = Auth::user();
        $this->account = Account::selectedAccount();
    }

    /**
     * @param View $view
     */
    public function compose(View $view)
    {
        $view->with([
            'account' => $this->account,
            'activeSubscription' => $this->activeSubscription(),
            'usersOnAccount' => $this->usersOnAccount(),
            'userCard' => $this->userCard(),
        ]);
    }

    protected function activeSubscription()
    {
        return $this->account->proxyToParent()->activeSubscriptions()->first();
    }

    protected function usersOnAccount()
    {
        return $this->account->proxyToParent()->users()->get();
    }

    protected function userCard()
    {
        $userCard = null;

        if ($customerId = $this->user->stripe_customer_id) {
            Stripe::setApiKey(Config::get('services.stripe.secret'));

            try {
                $customer = Customer::retrieve($customerId);
                $userCard = $customer ? $customer->sources->data[0] : null;
            } catch (Base $e) {
                Log::error($e->getMessage());
            }
        }

        return $userCard;
    }
}