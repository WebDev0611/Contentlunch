<?php

namespace App\Http\Controllers;

use App\Subscription;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Validator;

use App\Account;
use App\AccountType;

class AgencyController extends Controller {

    public function index ()
    {
        $accounts = collect([Auth::user()->agencyAccount()])
            ->merge(Auth::user()->agencyAccount()->childAccounts);

        return view('agency.index', compact('accounts'));
    }

    public function store (Request $request)
    {
        if (Auth::user()->cant('createSubaccount', Auth::user()->agencyAccount()))
        {
            return redirect()->route('agencyIndex')->with([
                'flash_message'      => 'This account has reached the maximum number of subaccounts on the current plan.' .
                    'Please upgrade your account plan or remove a subaccount to continue.',
                'flash_message_type' => 'danger',
            ]);
        }

        $validation = $this->validator($request->all());

        if ($validation->fails())
        {
            return redirect()->route('agencyIndex')->with('errors', $validation->errors());
        }

        $agencyAccount = Auth::user()->agencyAccount();
        $newAccount = Account::create([
            'name'              => $request->input('account_name'),
            'account_type_id'   => AccountType::COMPANY,
            'parent_account_id' => $agencyAccount->id
        ]);

        if (!$agencyAccount->activeSubscriptions()->isEmpty())
        {
            // Make a new Stripe payment and a subscription for the client on paid account
            $this->makeNewClientSubscription($agencyAccount, $newAccount);
        }

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
        $currentSubscription = $agencyAccount->activeSubscriptions()[0];
        $payUser = $agencyAccount->users()
            ->whereNotNull('stripe_customer_id')
            ->where('stripe_customer_id', '<>', '')
            ->first();

        // Prepare empty subscription object
        $sub = new Subscription();
        $sub->account()->associate($newAccount);
        $sub->subscriptionType()->associate($currentSubscription->subscriptionType);
        $sub->auto_renew = '1';

        try
        {
            Stripe::setApiKey(Config::get('services.stripe.secret'));
            \Stripe\ApiRequestor::setHttpClient(new \Stripe\HttpClient\CurlClient([CURLOPT_PROXY => '']));
            $planSlug = 'agency-client';

            try
            {
                $plan = \Stripe\Plan::retrieve($planSlug);
            } catch (\Stripe\Error\Base $e)
            {
                Log::error($e->getMessage());
            }

            if (empty($plan))
            {
                $plan = \Stripe\Plan::create([
                    "name"     => "CL Subscription Plan - " . $planSlug,
                    "id"       => $planSlug,
                    "interval" => 'month',
                    "currency" => "usd",
                    "amount"   => $currentSubscription->subscriptionType->price_per_client * 100
                ]);
            }

            $subscription = \Stripe\Subscription::create([
                "customer" => $payUser->stripe_customer_id,
                "plan"     => $plan->id,
                "metadata" => [
                    'accountName' => $newAccount->name,
                    'accountId' => $newAccount->id,
                    'parentAccountName' => $newAccount->parentAccount->name,
                    'parentAccountId' => $newAccount->parentAccount->id
                ]
            ]);
            $sub->start_date = date('Y-m-d', $subscription->current_period_start);
            $sub->expiration_date = date('Y-m-d', $subscription->current_period_end);
        } catch (\Stripe\Error\Base $e)
        {
            return redirect()
                ->route('subscription')
                ->with(['flash_message' => $e->getMessage(), 'flash_message_type' => 'danger']);
        } catch (\Exception $e)
        {
            return redirect()
                ->route('subscription')
                ->with(['flash_message' => $e->getMessage(), 'flash_message_type' => 'danger']);
        }

        if ($subscription->status !== "active")
        {
            throw new \Exception("Card not authorized");
        }

        $sub->save();
    }
}
