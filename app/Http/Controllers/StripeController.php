<?php

namespace App\Http\Controllers;

use App\Account;
use App\Subscription;
use App\SubscriptionType;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Config;
use Stripe\Stripe;

class StripeController extends Controller {

    public function webhook (Request $request)
    {
        Stripe::setApiKey(Config::get('services.stripe.secret'));

        $event = \Stripe\Event::retrieve($request->id); // Verify the event by fetching it from Stripe

        if (isset($event) && isset($event->data->object->customer)) {
            $object = $event->data->object;

            switch ($event->type) {
                case 'invoice.payment_failed' :
                    return $this->failedPayment($object);
                case 'customer.subscription.created':
                    return $this->createSubscription($object);
                case 'customer.subscription.updated':
                    return $this->updateSubscription($object);
                case 'customer.subscription.deleted':
                    return $this->deleteSubscription($object);
            }
        }
    }

    private function createSubscription ($object)
    {

    }

    private function updateSubscription ($object)
    {
        $subscription = Subscription::where('stripe_subscription_id', '=', $object->id)
            ->active()
            ->firstOrFail();
        $subscriptionType = SubscriptionType::whereSlug($object->plan->id)->firstOrFail();

        $subscription->subscriptionType()->associate($subscriptionType);
        $subscription->start_date = date('Y-m-d', $object->current_period_start);
        $subscription->expiration_date = date('Y-m-d', $object->current_period_end);
        $subscription->save();

        return response('ok', 200);
    }

    private function deleteSubscription ($object)
    {
        $subscription = Subscription::where('stripe_subscription_id', '=', $object->id)
            ->active()
            ->firstOrFail();

        $subscription->valid = false;
        $subscription->save();

        if ($subscription->account->parentAccount) {
            $subscription->account->enabled = false;
            $subscription->account->save();
        }

        return response('ok', 200);
    }

    private function failedPayment ($object)
    {
        //$customer = \Stripe\Customer::retrieve($object->customer);
        //$email = $customer->email;
        //$amount = sprintf('$%0.2f', $object->amount_due / 100.0);
    }
}
