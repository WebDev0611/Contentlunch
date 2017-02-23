<?php

namespace App\Http\Controllers;

use App\Account;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
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
        $data = [
            'user' => Auth::user(),
            'account' => Account::selectedAccount(),
        ];

        return view('settings.subscription', $data);
    }

    public function submitSubscription (Request $request) {

        // Validate the stripe token
        $validation = $this->validateCard($request->all());

        if ($validation->fails()) {
            return redirect()->route('subscription')->with('errors', $validation->errors());
        }

        // Try to charge their card
        try {
            $this->initStripe();
            $customer = $this->createStripeCustomer($request);
        } catch (\Stripe\Error\Base $e) {
            return $this->redirectToSubscription($e->getMessage(), 'danger');
        } catch (Exception $e) {
            return $this->redirectToSubscription($e->getMessage(), 'danger');
        }

        // Save Stripe customer ID
        $user = Auth::user();
        $user->stripe_customer_id = $customer->id;
        $user->save();

        try {
            $order = new \Stripe\Order(); // TODO
            $charge = $this->createStripeCharge($customer, $order);
        } catch (\Stripe\Error\Base $e) {
            return $this->redirectToSubscription($e->getMessage(), 'danger');
        } catch (\Exception $e) {
            return $this->redirectToSubscription($e->getMessage(), 'danger');
        }

        if ($charge->status !== "succeeded") {
            throw new Exception("Card not authorized");
        }

        // TODO update databadse
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

    protected function createStripeCharge ($customer, \Stripe\Order $order) {
        return \Stripe\Charge::create([
            'customer' => $customer->id,
            'amount' => 99 * 100, //TODO
            'currency' => 'usd',
            'description' => 'ContentLaunch Plan Subscription',
        ]);
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
