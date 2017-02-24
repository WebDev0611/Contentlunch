<?php

namespace App\Http\Controllers;

use App\Account;
use App\Http\Requests;
use App\Subscription;
use App\SubscriptionType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Stripe\Stripe;

class AccountSettingsController extends Controller {
    public function index (Request $request) {
        $data = [
            'user' => Auth::user(),
            'account' => Account::selectedAccount(),
        ];

        return view('settings.account', $data);
    }

    public function showSubscription () {
        $user = Auth::user();

        $data = [
            'user' => $user,
            'account' => Account::selectedAccount(),
        ];

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

        return view('settings.subscription', $data);
    }

    public function submitSubscription (Request $request) {
        
        // Validate the stripe token
        $validation = $this->validateCard($request->all());
        if ($validation->fails()) {
            return redirect()->route('subscription')->with('errors', $validation->errors());
        }

        try {
            $this->initStripe();
            $customerId = !empty($request->input('stripe-customer-id')) ? $request->input('stripe-customer-id') : $this->createStripeCustomer($request)->id;

            // Handle one-time payment or subscription
            if ($request->has('auto_renew') && $request->input('auto_renew') == '1') {
                $plan = $this->getStripePlan($request);
                $subscription = $this->createStripeSubscription($customerId, $plan->id);
            } else {
                $charge = $this->createStripeCharge($customerId, $request);
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

        // create new subscription and save it to DB
        $sub = new Subscription();
        $sub->account()->associate(Account::selectedAccount());
        $sub->subscriptionType()->associate(SubscriptionType::where('slug', $request->input('plan-name'))->first());
        $sub->active = true;
        $sub->auto_renew = $request->has('auto_renew') && $request->input('auto_renew') == '1';
        $sub->start_date = date("Y-m-d H:i:s");
        if ($request->input('plan-type') == "month") {
            $sub->expiration_date = date('Y-m-d H:i:s', strtotime(date("Y-m-d") . ' + 30 days'));
        } elseif ($request->input('plan-type') == "year") {
            $sub->expiration_date = date('Y-m-d H:i:s', strtotime(date("Y-m-d") . ' + 365 days'));
        }
        $sub->save();

        return $this->redirectToSubscription('Payment successful. Account upgrade is complete!');
    }

    private function validateCard (array $data) {
        return Validator::make($data, [
            'stripe-token' => 'required'
        ]);
    }

    private function initStripe () {
        Stripe::setApiKey(Config::get('services.stripe.secret'));
        \Stripe\ApiRequestor::setHttpClient(new \Stripe\HttpClient\CurlClient(array(CURLOPT_PROXY => '')));
    }

    protected function createStripeCustomer (Request $request) {
        $stripeToken = $request->input('stripe-token');

        return \Stripe\Customer::create([
            'email' => Auth::user()->email,
            'source' => $stripeToken
        ]);
    }

    protected function createStripeCharge ($customerId, $request) {
        return \Stripe\Charge::create([
            'customer' => $customerId,
            'amount' => $request->input('plan-price') * 100,
            'currency' => 'usd',
            'description' => 'ContentLaunch Plan Charge - ' . $request->input('plan-name') . '-' . $request->input('plan-type'),
        ]);
    }

    // Try to get existing plan from Stripe by id, or create a new one if it doesn't exist
    protected function getStripePlan ($request) {

        $planType = $request->input('plan-type');
        $planName = $request->input('plan-name');

        if ($planType != 'month' && $planType != 'year') {
            throw new \Exception("Plan type error");
        }

        $planId = $planType == 'month' ? $planName . "-monthly" : $planName . "-annually";
        Stripe::setApiKey(Config::get('services.stripe.secret'));

        try {
            $plan = \Stripe\Plan::retrieve($planId);
        } catch (\Stripe\Error\Base $e) {
            Log::error($e->getMessage());
        }

        if(!empty($plan)) {
            return $plan;
        }

        return \Stripe\Plan::create([
            "name" => "CL Subscription Plan - " . $planId,
            "id" => $planId,
            "interval" => $planType,
            "currency" => "usd",
            "amount" => $request->input('plan-price') * 100,
        ]);
    }

    protected function createStripeSubscription ($customerId, $planId) {
        return \Stripe\Subscription::create(array(
            "customer" => $customerId,
            "plan" => $planId,
        ));
    }

    protected function redirectToSubscription ($message, $message_type = 'success') {
        return redirect()
            ->route('subscription')
            ->with([
                'flash_message' => $message,
                'flash_message_type' => $message_type,
            ]);
    }

}
