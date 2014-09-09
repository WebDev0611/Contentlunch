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

  public function post_subscription($id, $checkAuth = true)
  {
    // Restrict user is in account
    if ($checkAuth && ! $this->inAccount($id)) {
      return $this->responseAccessDenied();
    }
    $sub = AccountSubscription::firstOrNew(['account_id' => $id]);
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
      // Attempt to do subscription charge
      // Will only do the charge if new account or matches expiration date rule
      if (app()->env != 'testing') {
        $balancedAccount = new Launch\Balanced($account);
        $balancedAccount->chargeAccount();
      }
      return $this->get_subscription($id, $checkAuth);
    }
    return $this->responseError($sub->errors()->toArray());
  }

}
