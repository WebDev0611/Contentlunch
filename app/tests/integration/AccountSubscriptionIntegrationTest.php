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

  public function testSaveAccountSubscriptionAttachesModulesToAccountLevel1()
  {
    $account = Woodling::saved('Account');
    $subscription1 = Woodling::saved('Subscription', array(
      'subscription_level' => 1
    ));
    $accountSub = Woodling::retrieve('AccountSubscription', array(
      'account_id' => $account->id,
      'subscription_level' => 1
    ));
    Artisan::call('db:seed', array(
      '--class' => 'ModuleSeeder'
    ));
    // When saving a level 1 subscription, the account should
    // only be assigned certain modules
    $response = $this->call('POST', '/api/account/'. $account->id .'/subscription', $accountSub->toArray());
    $response = $this->call('GET', '/api/account/'. $account->id);
    $data = $this->assertResponse($response);
    $modules = $data->modules;
    $expect = array('create', 'calendar', 'launch', 'measure');
    $accountModules = array();
    foreach ($modules as $module) {
      $accountModules[$module->name] = $module->name;
    }
    foreach ($expect as $name) {
      $this->assertEquals($name, $accountModules[$name]);
      unset($accountModules[$name]);
    }
    $this->assertEmpty($accountModules);
  }

  public function testSaveAccountSubscriptionAttachesModulesToAccountLevel2()
  {
    $account = Woodling::saved('Account');
    $subscription1 = Woodling::saved('Subscription', array(
      'subscription_level' => 2
    ));
    $accountSub = Woodling::retrieve('AccountSubscription', array(
      'account_id' => $account->id,
      'subscription_level' => 2
    ));
    Artisan::call('db:seed', array(
      '--class' => 'ModuleSeeder'
    ));
    // When saving a level 1 subscription, the account should
    // only be assigned certain modules
    $response = $this->call('POST', '/api/account/'. $account->id .'/subscription', $accountSub->toArray());
    $response = $this->call('GET', '/api/account/'. $account->id);
    $data = $this->assertResponse($response);
    $modules = $data->modules;
    $expect = array('create', 'calendar', 'launch', 'measure', 'collaborate');
    $accountModules = array();
    foreach ($modules as $module) {
      $accountModules[$module->name] = $module->name;
    }
    foreach ($expect as $name) {
      $this->assertEquals($name, $accountModules[$name]);
      unset($accountModules[$name]);
    }
    $this->assertEmpty($accountModules);
  }

  public function testSaveAccountSubscriptionAttachesModulesToAccountLevel3()
  {
    $account = Woodling::saved('Account');
    $subscription1 = Woodling::saved('Subscription', array(
      'subscription_level' => 3
    ));
    $accountSub = Woodling::retrieve('AccountSubscription', array(
      'account_id' => $account->id,
      'subscription_level' => 3
    ));
    Artisan::call('db:seed', array(
      '--class' => 'ModuleSeeder'
    ));
    // When saving a level 1 subscription, the account should
    // only be assigned certain modules
    $response = $this->call('POST', '/api/account/'. $account->id .'/subscription', $accountSub->toArray());
    $response = $this->call('GET', '/api/account/'. $account->id);
    $data = $this->assertResponse($response);
    $modules = $data->modules;
    $expect = array('create', 'calendar', 'launch', 'measure', 'collaborate', 'consult');
    $accountModules = array();
    foreach ($modules as $module) {
      $accountModules[$module->name] = $module->name;
    }
    foreach ($expect as $name) {
      $this->assertEquals($name, $accountModules[$name]);
      unset($accountModules[$name]);
    }
    $this->assertEmpty($accountModules);
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
