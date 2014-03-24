<?php

class SubscriptionController extends BaseController {

  public function index()
  {
    return Subscription::all();
  }

  public function show($id)
  {
    return Subscription::find($id);
  }

  public function update($id)
  {
    $sub = Subscription::find($id);
    $sub->licenses = Input::get('licenses');
    $sub->monthly_price = Input::get('monthly_price');
    $sub->annual_discount = Input::get('annual_discount');
    $sub->training = Input::get('training');
    $sub->features = Input::get('features');
    if ($sub->save()) {
      return $this->show($sub->id);
    }
    return Response::json(array(
      'errors' => $sub->errors()->toArray()
    ), 401);
  }

}
