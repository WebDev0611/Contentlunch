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
            $customer = \Stripe\Customer::retrieve($event->data->object->customer);
            $user = User::whereEmail($customer->email)->firstOrFail();

            switch ($event->type) {
                case 'invoice.payment_failed' :
                    $email = $customer->email;
                    $amount = sprintf('$%0.2f', $event->data->object->amount_due / 100.0);
                    break;
                case 'customer.subscription.created':
                    $this->createSubscription($event);
                    break;
                case 'customer.subscription.updated':
                    $this->updateSubscription($event);
                    break;
                case 'customer.subscription.deleted':
                    $this->deleteSubscription($event);
                    break;
            }

            return response($event, 200);
        }
        else {
            return response('error', 400);
        }
    }

    private function createSubscription ($event)
    {

    }

    private function updateSubscription ($event)
    {
        $subscription = Subscription::where('stripe_subscription_id', '=', $event->data->object->id)
        ->active()
        ->firstOrFail();

        $subscription->start_date = date('Y-m-d', $event->data->object->current_period_start);
        $subscription->expiration_date = date('Y-m-d', $event->data->object->current_period_end);
        $subscription->save();
    }

    private function deleteSubscription ($event)
    {

    }
}
