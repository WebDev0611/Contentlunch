<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

use App\Account;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;

class AccountController extends Controller {

    public function stats ()
    {
        $my_campaigns = Auth::user()->campaigns()->get();
        $my_tasks = Auth::user()->tasks->get();

        return view('home.index', [
            'mycampaigns' => $my_campaigns->toJson(),
        ]);
    }

    public function selectAccount (Request $request, Account $account)
    {
        Account::selectAccount($account);

        return response()->json(['account' => $account->id]);
    }

    public function disable (Request $request)
    {
        $account = Account::findOrFail($request->input('account_id'));
        $currentSubscriptions = $account->subscriptions()->active()->get();

        if ($currentSubscriptions->isEmpty()) {
            // This is the case with 'old' client subscriptions, when there was no subscription_id for client accounts.
            $data = [
                'accName' => $account->name,
                'parentAccName' => $account->parentAccount == null ? 'None' : $account->parentAccount->name,
                'userName' => Auth::user()->name,
                'userEmail' => Auth::user()->email
            ];

            Mail::send('emails.disable_subaccount', $data, function($message) {
                $message->from("no-reply@contentlaunch.com", "Content Launch")
                    ->to('jon@contentlaunch.com')
                    ->subject('Content Launch: Disabling a sub-account');
            });

            return $this->ajaxResponse('info',
                'Your subscription will have to be cancelled manually. Please contact Content Launch support.');
        }
        else {
            $this->initStripe();
            try {
                // Cancel Stripe subscription
                $stripeSubscription = \Stripe\Subscription::retrieve($currentSubscriptions->first()->stripe_subscription_id);
                $stripeSubscription->cancel();
            } catch (\Stripe\Error\Base $e) {
                return $this->ajaxResponse('error',
                    'Error occurred while trying to cancel the Stripe subscription. Please contact Content Launch support.');
            }

            // Cancel CL subscription
            $account->enabled = false;
            $account->save();
        }

        Account::selectAccount($account->parentAccount);

        return $account;
    }

    private function initStripe ()
    {
        Stripe::setApiKey(Config::get('services.stripe.secret'));
        \Stripe\ApiRequestor::setHttpClient(new \Stripe\HttpClient\CurlClient([CURLOPT_PROXY => '']));
    }

    private function ajaxResponse ($type, $message, $status = 500)
    {
        return response()->json([
            'type'    => $type,
            'message' => $message
        ], $status);
    }
}
