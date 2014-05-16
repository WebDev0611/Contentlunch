<?php

use Woodling\Woodling;

class CampaignIntegrationTest extends TestCase {

  protected $testAccount, $testCampaignType, $testUser;

  protected function setupData()
  {
    // Setup data needed for campaign tests
    $this->testAccount = Woodling::saved('Account');
    $this->testUser = Woodling::saved('User');
    $this->testCampaignType = Woodling::saved('CampaignType');
  }

  protected function assertCampaign($expected, $campaign)
  {
    foreach ([
      'account_id', 'user_id', 'title', 'status', 'campaign_type_id',
      'is_recurring', 'description', 'goals', 'concept'
    ] as $field) {
      $this->assertEquals($expected->$field, $campaign->$field, "Field: $field is not equal");
    }
    $this->assertNotEmpty($campaign->color);
  }

  public function testIndex()
  {
    $this->setupData();
    // Create some campaigns
    $campaigns = Woodling::savedList('Campaign', 3, [
      'account_id' => $this->testAccount->id,
      'user_id' => $this->testUser->id,
      'campaign_type_id' => $this->testCampaignType->id
    ]);
    $response = $this->call('GET', '/api/account/'. $this->testAccount->id .'/campaigns');
    $data = $this->assertResponse($response);
    $this->assertCampaign($campaigns[0], $data[0]);
    $this->assertCampaign($campaigns[1], $data[1]);
    $this->assertCampaign($campaigns[2], $data[2]);
  }

  public function testPost()
  {
    $this->setupData();
    // Campaign to create
    $campaign = Woodling::retrieve('Campaign', [
      'account_id' => $this->testAccount->id,
      'user_id' => $this->testUser->id,
      'campaign_type_id' => $this->testCampaignType->id
    ]);
    $response = $this->call('POST', '/api/account/'. $this->testAccount->id .'/campaigns', $campaign->toArray());
    $data = $this->assertResponse($response);
    $this->assertCampaign($campaign, $data);
  }

  public function testUpdate()
  {
    $this->setupData();
    // Campaign to update
    $campaign = Woodling::saved('Campaign', [
      'account_id' => $this->testAccount->id,
      'user_id' => $this->testUser->id,
      'campaign_type_id' => $this->testCampaignType->id
    ]);
    // Change data
    $campaign->title = 'New title';
    $campaign->status = 0;
    $campaign->is_recurring = ! $campaign->is_recurring;
    $campaign->description = 'Updated description';
    $campaign->goals = 'Updated goals';
    $campaign->concept = 'Updated concept';
    $response = $this->call('PUT', '/api/account/'. $this->testAccount->id .'/campaigns/'. $campaign->id, $campaign->toArray());
    $data = $this->assertResponse($response);
    $this->assertCampaign($campaign, $data);
  }

  public function testDelete()
  {
    $this->setupData();
    // Campaign to delete
    $campaign = Woodling::saved('Campaign', [
      'account_id' => $this->testAccount->id,
      'user_id' => $this->testUser->id,
      'campaign_type_id' => $this->testCampaignType->id
    ]);
    $response = $this->call('DELETE', '/api/account/'. $this->testAccount->id .'/campaigns/'. $campaign->id);
    $data = $this->assertResponse($response);
  }

}