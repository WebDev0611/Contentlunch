<?php

class StripeWebhookController extends BaseController {

    public function debug_update_account($accountId) {
        $account = Account::find($accountId)->first();
        $account->updateSubscriptionFromStripe();
        $account->save();
        return $account;
    }

    public function webhook()
    {
        $body = json_decode(Request::instance()->getContent());

        switch($body->type) {
            case "customer.subscription.created":
            case "customer.subscription.updated":
            case "customer.subscription.deleted":
                $this->handleSubscriptionEvent($body);
                break;
            case "invoice.created":
                $this->handleInvoiceCreated($body);
                break;
            case "invoice.payment_succeeded":
                $this->handlePaymentSucceeded($body);
                break;
        }

        return "ok stripe, thanks!";
    }


    /**
     * Occurs when stripe creates an invoice and gets ready to bill a customer.
     * We need to update the customer's quantity so the right amount gets billed
     * for per-user or per-client subscriptions.
     * @param $body
     */
    protected function handleInvoiceCreated($body) {
        $account = $this->getAccount($body->data->object->customer);

        $cu = \Stripe\Customer::retrieve($account->token);
        $cu->quantity = $account->quantity();
        $cu->save();
    }


    /** Occurs when stripe processes a payment.  We should update our internal representation
     * of the customer's subscription.
     */
    protected function handlePaymentSucceeded($body) {
        $account = $this->getAccount($body->data->object->customer);
        $account->updateSubscriptionFromStripe();
    }

    /**
     * Called on stripe subscription events (create, update, delete).
     * We should update our internal representation of the user's subscription.
     *
     * @param $body
     */
    protected function handleSubscriptionEvent($body) {
        $account = $this->getAccount($body->data->object->customer);
        $account->updateSubscriptionFromStripe();
    }

    /**
     * Given a stripe customer id, return the associated account.
     *
     * @param $stripeCustomerId
     */
    protected function getAccount($stripeCustomerId) {
        return Account::where('token', $stripeCustomerId)->first();
    }


}
