<?php

class AccountSubscriptionController extends BaseController {

  public function get_subscription($id, $checkAuth = true)
  {
    // Restrict user is in account
    if ($checkAuth && ! $this->inAccount($id)) {
      return $this->responseAccessDenied();
    }
    return Account::find($id)->accountSubscription()->orderBy('id', 'desc')->first();
  }


  public function subscribe() {
    /** This is the handler that takes the user's stripe token, and subscribes them to a plan.
     * It will create a stripe customer.
     * If the user already has a subscription, it allows them to switch their plan.
     * */

  }



  public function create_subscription($account_id,
                                      $subscription_level,
                                      $licenses,
                                      $monthly_price,
                                      $annual_discount,
                                      $training,
                                      $features,
                                      $subscription_type) {
    $sub = AccountSubscription::firstOrNew(['account_id' => $account_id]);
    $sub->account_id = $account_id;
    $sub->subscription_level = $subscription_level;
    $sub->licenses = $licenses;
    $sub->monthly_price = $monthly_price;
    $sub->annual_discount = $annual_discount;
    $sub->training = $training;
    $sub->features = $features;
    $sub->subscription_type = $subscription_type;
    if ($sub->save()) {
      // Based on the subscription level, assign modules to the account
      switch ($sub->subscription_level) {
        case 1:
          $names = array('create', 'calendar', 'launch', 'measure', 'promote');
          break;
        case 2:
          $names = array('create', 'calendar', 'launch', 'measure', 'collaborate', 'promote');
          break;
        case 3:
          $names = array('create', 'calendar', 'launch', 'measure', 'collaborate', 'consult', 'promote');
          break;
      }
      $modules = Module::whereIn('name', $names)->get();
      $syncModules = array();
      foreach ($modules as $module) {
        $syncModules[] = $module->id;
      }
      $account = Account::find($sub->account_id);
      $account->modules()->sync($syncModules);
      $account->updateUniques();
    }
    return $sub;
  }

  public function cancel_subscription($account_id)
  {
    // Restrict user is in account
    if (! $this->inAccount($account_id)) {
      return $this->responseAccessDenied();
    }

    $sub = AccountSubscription::firstOrNew(['account_id' => $account_id]);
    $account = $sub->account()->first();

    $stripeKey = Config::get('app.stripe')['secret_key'];

    \Stripe\Stripe::setApiKey($stripeKey);

    if($account->token) {
      // Need to update an existing stripe customer
      $subscriptions = \Stripe\Customer::retrieve($account->token)->subscriptions->all();
      foreach($subscriptions->data as &$sub) {
        $sub->cancel();
      }
      $account->token = null;
      $account->save();

    }

  }



  public function post_subscription($account_id, $checkAuth = true)
  {
    // Restrict user is in account
    if ($checkAuth && ! $this->inAccount($account_id)) {
      return $this->responseAccessDenied();
    }

    $sub = AccountSubscription::firstOrNew(['account_id' => $account_id]);

    $stripe_token = Input::get('token');
    $plan_id = Input::get('plan_id');

    if($plan_id) {
      $this->switch_plan($sub, $plan_id, $stripe_token);
    } else {
      $this->update_payment($sub, $stripe_token);
    }

    if($sub->exists()) {
      return $this->get_subscription($account_id, $checkAuth);
    }
    return $this->responseError($sub->errors()->toArray());
  }

  /**
   * Allow a customer to renew their own account
   */
  public function renew_subscription($id)
  {
    if (!$this->inAccount($id)) {
      return $this->responseAccessDenied();
    }
  }

  protected function update_quantity($account_subscription) {
    $account = $account_subscription->account()->first();
    $stripeKey = Config::get('app.stripe')['secret_key'];

    \Stripe\Stripe::setApiKey($stripeKey);

    if($account->token) {
      // Need to update an existing stripe customer
      $cu = \Stripe\Customer::retrieve($account->token);
      $cu->quantity = $account->quantity();
      $cu->save();
    } else {
      throw new Exception('Can not update non-existent customer');
    }
  }


  protected function update_payment($account_subscription, $stripe_token) {
    $account = $account_subscription->account()->first();
    $stripeKey = Config::get('app.stripe')['secret_key'];
    \Stripe\Stripe::setApiKey($stripeKey);
    if($account->token) {
      // Need to update an existing stripe customer
      $cu = \Stripe\Customer::retrieve($account->token);
      if($stripe_token) {
        $cu->source = $stripe_token;
      }
      $cu->save();
    } else {
      if(!$stripe_token) {
        throw new Exception('Can not find payment details for customer.');
      }
      // Need to create a new stripe customer
      $cu = \Stripe\Customer::create(array(
          "description" => "{$account->name} {$account->id}",
          "email" => $account->email,
          "metadata" => [
              "account_id" => $account->id
          ],
          "source" => $stripe_token
      ));
      $account->token = $cu->id;
      $account->save();
    }
  }

  protected function switch_plan($account_subscription, $plan_id, $stripe_token=null) {
    $account = $account_subscription->account()->first();
    $plan = Subscription::find($plan_id);
    $stripeKey = Config::get('app.stripe')['secret_key'];

    \Stripe\Stripe::setApiKey($stripeKey);

    if($account->token) {
      // Need to update an existing stripe customer
      $cu = \Stripe\Customer::retrieve($account->token);
      $cu->plan = $plan->stripe_id;
      $cu->quantity = $account->quantity();
      if($stripe_token) {
        $cu->source = $stripe_token;
      }
      $cu->save();
    } else {
      if(!$stripe_token) {
        throw new Exception('Can not find payment details for customer.');
      }
      // Need to create a new stripe customer
      $cu = \Stripe\Customer::create(array(
          "description" => "{$account->name} {$account->id}",
          "email" => $account->email,
          "metadata" => [
            "account_id" => $account->id
          ],
          "plan" => $plan->stripe_id,
          "quantity" => $account->quantity(),
          "source" => $stripe_token
      ));
      $account->token = $cu->id;
      $account->save();
    }



    $account_subscription->save();
    return $account_subscription;
  }

}
