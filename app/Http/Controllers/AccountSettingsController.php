<?php

namespace App\Http\Controllers;

use App\Account;
use App\Subscription;
use App\SubscriptionType;
use Illuminate\Http\Request;
use Auth;
use Config;
use Log;
use Validator;
use Stripe\Stripe;

class AccountSettingsController extends Controller {

    public function index()
    {
        $data = [
            'user'    => Auth::user(),
            'account' => Account::selectedAccount(),
        ];

        return view('settings.account', $data);
    }

    public function showSubscription ()
    {
        return view('settings.subscription', $this->prepareData());
    }

    public function showSubscriptionClients ()
    {
        $account = Account::selectedAccount();

        if (!$account->isAgencyAccount() && !$account->isSubAccount()) {
            return redirect(route('subscription'));
        }

        $data = $this->prepareData();
        $data['accounts'] = Auth::user()->agencyAccount()->childAccounts;

        return view('settings.subscription-clients', $data);
    }

    public function submitSubscription (Request $request)
    {
        // Validate the stripe token
        $validation = $this->validateCard($request->all());
        if ($validation->fails()) {
            return redirect()->route('subscription')->with('errors', $validation->errors());
        }

        $isAutoRenew = $request->has('auto_renew') && $request->input('auto_renew') == '1';

        $subscriptionData = [];
        $subscriptionData['auto_renew'] = $isAutoRenew;

        // Stripe
        try {
            $this->initStripe();
            $customerId = !empty($request->input('stripe-customer-id')) ? $request->input('stripe-customer-id') : $this->createStripeCustomer($request)->id;

            // Handle one-time payment or subscription
            if ($isAutoRenew) {
                // Stripe Subscription
                $plan = $this->getStripePlan($request);
                $subscription = $this->createStripeSubscription($customerId, $plan->id);

                $subscriptionData['start_date'] = date('Y-m-d', $subscription->current_period_start);
                $subscriptionData['expiration_date'] = date('Y-m-d', $subscription->current_period_end);
            }
            else {
                // Stripe Charge
                $charge = $this->createStripeCharge($customerId, $request);

                $subscriptionData['start_date'] = date("Y-m-d");
                if ($request->input('plan-type') == "month") {
                    $subscriptionData['expiration_date'] = date('Y-m-d', strtotime(date("Y-m-d") . ' + 30 days'));
                }
                elseif ($request->input('plan-type') == "year") {
                    $subscriptionData['expiration_date'] = date('Y-m-d', strtotime(date("Y-m-d") . ' + 365 days'));
                }
            }
        } catch (\Stripe\Error\Base $e) {
            return $this->redirectToSubscription($e->getMessage(), 'danger');
        } catch (\Exception $e) {
            return $this->redirectToSubscription($e->getMessage(), 'danger');
        }

        if ((isset($charge) && $charge->status !== "succeeded") || (isset($subscription) && $subscription->status !== "active")) {
            throw new \Exception("Card not authorized");
        }

        // Save Stripe customer ID
        $user = Auth::user();
        $user->stripe_customer_id = $customerId;
        $user->save();

        // Save new subscription
        $subscriptionType = SubscriptionType::where('slug', $request->input('plan-slug'))->first();
        Account::selectedAccount()->subscribe($subscriptionType, $subscriptionData);

        return $this->redirectToSubscription('Payment successful. Account upgrade is complete!');
    }

    private function validateCard (array $data)
    {
        return Validator::make($data, [
            'stripe-token' => 'required_without:stripe-customer-id'
        ]);
    }

    private function initStripe ()
    {
        Stripe::setApiKey(Config::get('services.stripe.secret'));
        \Stripe\ApiRequestor::setHttpClient(new \Stripe\HttpClient\CurlClient([CURLOPT_PROXY => '']));
    }

    protected function createStripeCustomer (Request $request)
    {
        $stripeToken = $request->input('stripe-token');

        return \Stripe\Customer::create([
            'email'  => Auth::user()->email,
            'source' => $stripeToken
        ]);
    }

    protected function createStripeCharge ($customerId, $request)
    {
        return \Stripe\Charge::create([
            'customer'    => $customerId,
            'amount'      => $request->input('plan-price') * 100,
            'currency'    => 'usd',
            'description' => 'ContentLaunch Plan Charge - ' . $request->input('plan-slug')
        ]);
    }

    // Try to get existing plan from Stripe by id, or create a new one if it doesn't exist
    protected function getStripePlan ($request)
    {
        $planType = $request->input('plan-type');
        $planSlug = $request->input('plan-slug');

        if ($planType != 'month' && $planType != 'year') {
            throw new \Exception("Plan type error");
        }

        Stripe::setApiKey(Config::get('services.stripe.secret'));
        try {
            $plan = \Stripe\Plan::retrieve($planSlug);
        } catch (\Stripe\Error\Base $e) {
            Log::error($e->getMessage());
        }

        if (!empty($plan)) {
            return $plan;
        }

        return \Stripe\Plan::create([
            "name"     => "CL Subscription Plan - " . $planSlug,
            "id"       => $planSlug,
            "interval" => $planType,
            "currency" => "usd",
            "amount"   => $request->input('plan-price') * 100,
        ]);
    }

    protected function createStripeSubscription ($customerId, $planId)
    {
        return \Stripe\Subscription::create([
            "customer" => $customerId,
            "plan"     => $planId,
        ]);
    }

    protected function redirectToSubscription ($message, $message_type = 'success')
    {
        return redirect()
            ->route('subscription')
            ->with([
                'flash_message'      => $message,
                'flash_message_type' => $message_type,
            ]);
    }

    private function prepareData ()
    {
        $user = Auth::user();
        $account = Account::selectedAccount();

        if($account->parentAccount == null){
            $activeSubscription = $account->activeSubscriptions()->first();
            $usersOnAccount = $account->users;
        } else {
            $activeSubscription = $account->parentAccount->activeSubscriptions()->first();
            $usersOnAccount = $account->parentAccount->users;
        }

        $data = [
            'user'    => $user,
            'account' => $account,
            'activeSubscription' => $activeSubscription,
            'usersOnAccount' => $usersOnAccount
        ];

        // Get plan prices
        foreach (SubscriptionType::all() as $subscriptionType) {
            $planPrices[$subscriptionType->slug] = number_format($subscriptionType->price, 0, '', '');
        }
        $data['planPrices'] = $planPrices;

        if (!empty($user->stripe_customer_id)) {
            // Get user's first saved card
            Stripe::setApiKey(Config::get('services.stripe.secret'));
            try {
                $customer = \Stripe\Customer::retrieve($user->stripe_customer_id);
                $data['userCard'] = isset($customer) ? $customer->sources->data[0] : null;
            } catch (\Stripe\Error\Base $e) {
                Log::error($e->getMessage());
            }
        }

        return $data;
    }

}
