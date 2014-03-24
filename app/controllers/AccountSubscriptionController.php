<?php

class AccountSubscriptionController extends BaseController {

  public function show($id)
  {
    //return Account
  }

  public function get_subscription($id)
  {
    return Account::find($id)->accountSubscription()->orderBy('id', 'desc')->first();
  }

  public function post_subscription($id)
  {
    $sub = new AccountSubscription;
    $sub->account_id = $id;
    $sub->auto_renew = Input::get('auto_renew');
    $sub->licenses = Input::get('licenses');
    $sub->payment_type = Input::get('payment_type');
    $sub->subscription_id = Input::get('subscription');
    $sub->token = Input::get('token');
    $sub->yearly_payment = Input::get('yearly_payment');
    $sub->save();
    return $sub;
  }

}
