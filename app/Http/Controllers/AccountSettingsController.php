<?php

namespace App\Http\Controllers;

use App\Account;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Stripe\ApiRequestor;
use Stripe\HttpClient\CurlClient;
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

        return 'a';

        // Try to charge their card before making the order...
        $this->initStripe();
        $user = Auth::user();

        // Get/create stripe customer
        try {
            if ($user->stripe_customer_id !== null) {
                $customerId = $user->stripe_customer_id;
            } else {
                $customer = $this->createStripeCustomer($request);
                $customerId = $customer->id;
                $user->stripe_customer_id = $customerId;
                $user->save();
            }
        } catch (Exception $e) {
            echo $e->getMessage();
            echo $e->getTrace();
            // TODO: We need to do something here to let the user know we could not process the payment.
            die();
        }

        try {
            $charge = $this->createStripeCharge($customer, $order);
        } catch (\Exception $e) {
            return $this->redirectToSubscription($e->getMessage(), 'danger');
        }

        if($charge->status !== "succeeded"){
            throw new Exception("Card not authorized");
        }
    }

    private function validateCard (array $data) {
        return Validator::make($data, [
            'stripe-token' => 'required'
        ]);
    }

    private function initStripe () {
        Stripe::setApiKey(Config::get('services.stripe.secret'));
        ApiRequestor::setHttpClient(new CurlClient(array(CURLOPT_PROXY => '')));
    }

    protected function createStripeCustomer (Request $request) {
        $stripeToken = $request->input('stripe-token');

        return \Stripe\Customer::create([
            'email' => Auth::user()->email,
            'source' => $stripeToken
        ]);
    }

    protected function createStripeCharge ($customer, Order $order) {
        return \Stripe\Charge::create([
            'customer' => $customer->id,
            'amount' => $order->getPrice() * 100,
            'currency' => 'usd',
            'description' => 'ContentLaunch Plan Subscription',
        ]);
    }

    protected function redirectToSubscription($message, $message_type = 'success')
    {
        return redirect()
            ->route('subscription')
            ->with([
                'flash_message' => $message,
                'flash_message_type' => $message_type,
            ]);
    }

}
