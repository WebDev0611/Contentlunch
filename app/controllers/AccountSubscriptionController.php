<?php

class AccountSubscriptionController extends BaseController {

  public function get_subscription($id)
  {
    return Account::find($id)->accountSubscription()->orderBy('id', 'desc')->first();
  }

  public function post_subscription($id)
  {
    $sub = new AccountSubscription;
    $sub->account_id = $id;
    $sub->subscription_level = Input::get('subscription_level');
    $sub->licenses = Input::get('licenses');
    $sub->monthly_price = Input::get('monthly_price');
    $sub->annual_discount = Input::get('annual_discount');
    $sub->training = Input::get('training');
    $sub->features = Input::get('features');
    if ($sub->save()) {
      return $this->get_subscription($id);
    }
    return $this->errorResponse($sub->errors()->toArray());
  }

}
