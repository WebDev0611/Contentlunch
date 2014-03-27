<?php

use Woodling\Woodling;

class AccountSubscriptionIntegrationTest extends TestCase {

  public function testGetAccountSubscription()
  {
    $account = Woodling::saved('Account');
    $subscription = Woodling::saved('Subscription');
    $subscriptions = Woodling::savedList('AccountSubscription', 5, array(
      'account_id' => $account->id
    ));
    $response = $this->call('GET', '/api/account/'. $account->id .'/subscription');
    $data = $this->assertResponse($response);
    $this->assertSubscription($subscriptions[4], $data);
  }

  public function testSaveAccountSubscriptionReturnsSubscriptionObject()
  {
    $account = Woodling::saved('Account');
    $subscription = Woodling::saved('Subscription');
    $accountSub = Woodling::retrieve('AccountSubscription', array(
      'account_id' => $account->id
    ));
    $response = $this->call('POST', '/api/account/'. $account->id .'/subscription', $accountSub->toArray());
    $data = $this->assertResponse($response);
    $accountSub->id = $data->id;
    $this->assertSubscription($accountSub, $data);
  }

  public function testGetAccountSubscriptionsFromAccountCall()
  {
    $accounts = Woodling::savedList('Account', 3);
    $subscriptions = array();
    foreach ($accounts as $account) {
      $subscriptions[] = Woodling::saved('AccountSubscription', array(
        'account_id' => $account->id
      ));
    }
    $response = $this->call('GET', '/api/account');
    $data = $this->assertResponse($response);
    foreach ($accounts as $key => $account) {
      $this->assertAccount($account, $data[$key]);
      $this->assertSubscription($subscriptions[$key], $account->account_subscription);
    }
  }

  public function testGetAccountSubscriptionFromAccountCall()
  {
    $account = Woodling::saved('Account');
    $subscription = Woodling::saved('AccountSubscription', array(
      'account_id' => $account->id
    ));
    $response = $this->call('GET', '/api/account/'. $account->id);
    $data = $this->assertResponse($response);
    $this->assertSubscription($subscription, $account->account_subscription);
  }

}
