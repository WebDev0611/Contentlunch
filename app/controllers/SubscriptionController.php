<?php

class SubscriptionController extends BaseController {

  public function index()
  {
    return Subscription::all();
  }

  public function show($id)
  {
    if ($subscription = Subscription::find($id)) {
      return $subscription;
    }
    return $this->responseError("Record not found");
  }

  public function update($id)
  {
    $sub = Subscription::find($id);
    if ( ! $sub) {
      return $this->responseError("Record not found");
    }
    $sub->licenses = Input::get('licenses');
    $sub->monthly_price = Input::get('monthly_price');
    $sub->annual_discount = Input::get('annual_discount');
    $sub->training = Input::get('training');
    $sub->features = Input::get('features');
    if ($sub->save()) {
      return $this->show($sub->id);
    }
    return $this->responseError($sub->errors()->toArray());
  }

}
