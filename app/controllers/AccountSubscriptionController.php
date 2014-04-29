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
      // Based on the subscription level, assign modules to the account
      switch ($sub->subscription_level) {
        case 1:
          $names = array('create', 'calendar', 'launch', 'measure');
        break;
        case 2:
          $names = array('create', 'calendar', 'launch', 'measure', 'collaborate');
        break;
        case 3:
          $names = array('create', 'calendar', 'launch', 'measure', 'collaborate', 'consult');
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
      // Attempt to do subscription charge
      // Will only do the charge if new account or matches expiration date rule
      $balancedAccount = new Launch\Balanced($account);
      $balancedAccount->chargeAccount();
      return $this->get_subscription($id);
    }
    return $this->errorResponse($sub->errors()->toArray());
  }

}
