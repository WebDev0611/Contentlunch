<?php

namespace App\Http\Controllers;

use App\Limit;
use App\Subscription;
use App\SubscriptionType;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Validator;

use App\Account;
use App\AccountType;

class AgencyController extends Controller {

    public function index ()
    {
        $accounts = collect([Auth::user()->agencyAccount()])
            ->merge(Auth::user()->agencyAccount()->activeChildAccounts);

        return view('agency.index', compact('accounts'));
    }

    public function store (Request $request)
    {
        if (Auth::user()->cant('createSubaccount', Auth::user()->agencyAccount())) {
            return redirect()->route('agencyIndex')->with([
                'flash_message'      => Limit::feedbackMessage('subaccounts_per_account'),
                'flash_message_type' => 'danger',
            ]);
        }
        Auth::user()->addToLimit('subaccounts_per_account');

        $validation = $this->validator($request->all());

        if ($validation->fails()) {
            return redirect()->route('agencyIndex')->with('errors', $validation->errors());
        }

        $agencyAccount = Auth::user()->agencyAccount();
        $newAccount = Account::create([
            'name'              => $request->input('account_name'),
            'account_type_id'   => AccountType::AGENCY,
            'parent_account_id' => $agencyAccount->id
        ]);

        // Notice all users on the account about new subaccount
        $emails = $agencyAccount->users()->pluck('email')->toArray();
        Mail::send('emails.new_subaccount', ['accName' => $newAccount->name], function ($message) use ($emails) {
            $message->from("no-reply@contentlaunch.com", "Content Launch")
                ->to($emails)
                ->subject('New sub-account added');
        });

        // Make a new Stripe subscription
        $this->makeNewClientSubscription($agencyAccount, $newAccount);

        return redirect()->route('agencyIndex')->with([
            'flash_message'      => 'Sub-Account created successfully.',
            'flash_message_type' => 'success'
        ]);
    }

    public function validator (array $data)
    {
        return Validator::make($data, [
            'account_name' => 'required'
        ]);
    }

    private function makeNewClientSubscription ($agencyAccount, $newAccount)
    {
        $currentSubscription = $agencyAccount->activeSubscriptions()->first();
        $payUser = $agencyAccount->users()
            ->whereNotNull('stripe_customer_id')
            ->where('stripe_customer_id', '<>', '')
            ->first();

        $freeSubscriptionType = SubscriptionType::whereSlug('free')->first();

        // Allow agency to use free sub-accounts limit
        if ($agencyAccount->childAccounts()->count() > $freeSubscriptionType->limit('subaccounts_per_account')) {

            // Prepare empty subscription object
            $subscriptionData = [];
            $subscriptionData['auto_renew'] = 1;

            try {
                Stripe::setApiKey(Config::get('services.stripe.secret'));
                \Stripe\ApiRequestor::setHttpClient(new \Stripe\HttpClient\CurlClient([CURLOPT_PROXY => '']));
                $planSlug = 'agency-client';

                try {
                    $plan = \Stripe\Plan::retrieve($planSlug);
                } catch (\Stripe\Error\Base $e) {
                    Log::error($e->getMessage());
                }

                if (empty($plan)) {
                    $plan = \Stripe\Plan::create([
                        "name"     => "CL Subscription Plan - " . $planSlug,
                        "id"       => $planSlug,
                        "interval" => 'month',
                        "currency" => "usd",
                        "amount"   => $currentSubscription->subscriptionType->price_per_client * 100
                    ]);
                }

                // Stripe subscription
                $subscription = \Stripe\Subscription::create([
                    "customer" => $payUser->stripe_customer_id,
                    "plan"     => $plan->id,
                    "metadata" => [
                        'accountName'       => $newAccount->name,
                        'accountId'         => $newAccount->id,
                        'parentAccountName' => $newAccount->parentAccount->name,
                        'parentAccountId'   => $newAccount->parentAccount->id
                    ]
                ]);
                $subscriptionData['stripe_subscription_id'] = $subscription->id;
                $subscriptionData['start_date'] = date('Y-m-d', $subscription->current_period_start);
                $subscriptionData['expiration_date'] = date('Y-m-d', $subscription->current_period_end);
            } catch (\Stripe\Error\Base $e) {
                return redirect()
                    ->route('subscription')
                    ->with(['flash_message' => $e->getMessage(), 'flash_message_type' => 'danger']);
            } catch (\Exception $e) {
                return redirect()
                    ->route('subscription')
                    ->with(['flash_message' => $e->getMessage(), 'flash_message_type' => 'danger']);
            }

            if ($subscription->status !== "active") {
                throw new \Exception("Card not authorized");
            }

            $newAccount->subscribe(SubscriptionType::whereSlug('agency-client')->first(), $subscriptionData, false);
        }
        else {
            $subscriptionData['start_date'] = date('Y-m-d');
            $subscriptionData['expiration_date'] = '0000-00-00';

            $newAccount->subscribe($freeSubscriptionType, $subscriptionData, false);
        }
    }
}
