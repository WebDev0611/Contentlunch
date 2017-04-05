<?php

namespace App\Http\Controllers;

use App\Subscription;
use App\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Config;
use Stripe\Stripe;

class StripeController extends Controller {

    public function webhook (Request $request)
    {
        Stripe::setApiKey(Config::get('services.stripe.secret'));

        $event = \Stripe\Event::retrieve($request->id);

        if (isset($event) && isset($event->data->object->customer)) {
            $event = \Stripe\Event::retrieve($event->id); // Verify the event by fetching it from Stripe
            $object = $event->data->object;

            switch ($event->type) {
                case 'invoice.payment_failed' :
                    $this->failedPayment($object);
                    break;
                case 'customer.subscription.created':
                    $this->createSubscription($object);
                    break;
                case 'customer.subscription.updated':
                    $this->updateSubscription($object);
                    break;
                case 'customer.subscription.deleted':
                    $this->deleteSubscription($object);
                    break;
            }

            return response($event, 200);
        }
        else {
            return response('error', 400);
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

        $subscription->start_date = date('Y-m-d', $object->current_period_start);
        $subscription->expiration_date = date('Y-m-d', $object->current_period_end);
        $subscription->save();
    }

    private function deleteSubscription ($object)
    {
        $subscription = Subscription::where('stripe_subscription_id', '=', $object->id)
            ->active()
            ->firstOrFail();

        $subscription->valid = false;
        $subscription->save();
    }

    private function failedPayment ($object)
    {
        //$customer = \Stripe\Customer::retrieve($object->customer);
        //$email = $customer->email;
        //$amount = sprintf('$%0.2f', $object->amount_due / 100.0);
    }
}
