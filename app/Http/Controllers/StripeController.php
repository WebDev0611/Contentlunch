<?php

namespace App\Http\Controllers;

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

        if (isset($event)) {

            $customer = \Stripe\Customer::retrieve($event->data->object->customer);
            $user = User::whereEmail($customer->email)->firstOrFail();

            switch ($event->type) {
                case 'invoice.payment_failed' :
                    $email = $customer->email;
                    $amount = sprintf('$%0.2f', $event->data->object->amount_due / 100.0);
                    break;
                case 'customer.subscription.updated':
                    $this->updateSubscription();
                    break;
                case 'customer.subscription.deleted':
                    $this->deleteSubscription();
                    break;
                default:
                    // Return 2xx status for events that we're not dealing with, so Stripe doesn't have to re-send them
                    return response('ignoring', 202);
                    break;
            }

            return response($event, 200);
        }
        else {
            return response('error', 400);
        }
    }

    private function updateSubscription ()
    {

    }

    private function deleteSubscription ()
    {

    }
}
