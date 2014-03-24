<?php

use Woodling\Woodling;

class SubscriptionIntegrationTest extends TestCase {

  public function testGetAll()
  {
    $subs = Woodling::savedList('Subscription', 3);
    $response = $this->call('GET', '/api/subscription');
    $data = $this->assertResponse($response);
    foreach ($subs as $key => $sub) {
      $this->assertSubscription($sub, $data[$key]);
    }
  }

  public function testShowRecord()
  {
    $sub = Woodling::saved('Subscription');
    $response = $this->call('GET', '/api/subscription/'. $sub->id);
    $data = $this->assertResponse($response);
    $this->assertSubscription($sub, $data);
  }

  public function testShowNonExistantRecordReturnsError()
  {
    $response = $this->call('GET', '/api/subscription/999');
    $data = $this->assertResponse($response, true);
    $this->assertContains('found', $data->errors[0]);
  }

  public function testUpdateRecordSuccessReturnsSubscriptionObject()
  {
    $sub = Woodling::saved('Subscription');
    $changes = Woodling::retrieve('Subscription');
    $response = $this->call('PUT', '/api/subscription/'. $sub->id, $changes->toArray());
    $data = $this->assertResponse($response);
    $changes->id = $sub->id;
    $this->assertSubscription($changes, $data);
  }

  public function testUpdateNonExistantRecordReturnsError()
  {
    $response = $this->call('PUT', '/api/subscription/999');
    $data = $this->assertResponse($response, true);
    $this->assertContains('found', $data->errors[0]);
  }

}
