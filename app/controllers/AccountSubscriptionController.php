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

  public function post_subscription($id, $checkAuth = true)
  {
    // Restrict user is in account
    if ($checkAuth && ! $this->inAccount($id)) {
      return $this->responseAccessDenied();
    }

    $sub = $this->create_subscription($id,
                Input::get('subscription_level'),
                Input::get('licenses'),
                Input::get('monthly_price'),
                Input::get('annual_discount'),
                Input::get('training'),
                Input::get('features'));

    if($sub->exists()) {
      return $this->get_subscription($id, $checkAuth);
    }
    return $this->responseError($sub->errors()->toArray());
  }

  /**
   * Allow a customer to renew their own account
   */
  public function renew_subscription($id)
  {
    if ( ! $this->inAccount($id)) {
      return $this->responseAccessDenied();
    }
    try {
      $account = Account::find($id);
      $balanced = new Launch\Balanced($account);
      //$payment = $balanced->chargeAccount(true);
    } catch (\Balanced\Errors\Declined $e) {
      return $this->responseError('Error processing transaction. The transaction was declined.');
    } catch (\Balanced\Errors\NoFundingSource $e) {
      return $this->responseError('Error processing transaction. No active funding sources.');
    } catch (\Balanced\Errors\CannotDebit $e) {
      return $this->responseError('Error processing transaction. No debitable funding sources.');
    } catch (\Balanced\Errors\InvalidRoutingNumber $e) {
      return $this->responseError('Error processing transaction. Routing number is invalid.');
    } catch (\Balanced\Errors\BankAccountVerificationFailure $e) {
      return $this->responseError('Error processing transaction. Unable to verify bank account.'. $e->description);
    } catch (\Exception $e) {
      return $this->responseError('Error processing transaction.');
    }
    $account = $account->toArray();
    //$retPayment = $payment->toArray();
    //$account['payment'] = $payment->toArray();
    return $account;
  }

}
