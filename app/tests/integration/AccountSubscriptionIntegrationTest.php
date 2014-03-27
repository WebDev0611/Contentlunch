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

}
