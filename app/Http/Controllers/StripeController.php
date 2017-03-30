<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Config;
use Stripe\Stripe;

class StripeController extends Controller {

    public function webhook (Request $request)
    {
        return response('ok', 200);
        Stripe::setApiKey(Config::get('services.stripe.secret'));

        $event_json = json_decode($request->all());
        $event = \Stripe\Event::retrieve($event_json->id);

        if (isset($event)) {
            switch ($event->type) {
                case 'invoice.payment_failed' :
                    $customer = \Stripe\Customer::retrieve($event->data->object->customer);
                    $email = $customer->email;
                    $amount = sprintf('$%0.2f', $event->data->object->amount_due / 100.0);
                    // TODO
                    break;
                case 'customer.subscription.updated':
                    // TODO
                    break;
                case 'customer.subscription.deleted':
                    // TODO
                    break;
            }

            return response($event, 200);
        }
        else {
            return response('error', 404);
        }
    }
}
