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
    // Collaborators
    $users = Woodling::savedList('User', 2);
    // Campaign to create
    $campaign = Woodling::retrieve('Campaign', [
      'account_id' => $this->testAccount->id,
      'user_id' => $this->testUser->id,
      'campaign_type_id' => $this->testCampaignType->id,
      'tags' => [
        ['tag' => 'Tag 1'],
        ['tag' => 'Tag 2']
      ],
      'collaborators' => [
        $users[0]->toArray(),
        $users[1]->toArray()
      ]
    ]);
    
    $response = $this->call('POST', '/api/account/'. $this->testAccount->id .'/campaigns', $campaign->toArray());
    $data = $this->assertResponse($response);
    $this->assertCampaign($campaign, $data);
    // Make sure tags were attached
    $this->assertEquals('Tag 1', $data->tags[0]->tag);
    $this->assertEquals('Tag 2', $data->tags[1]->tag);
    // Make sure collaborators were attached
    $this->assertEquals($users[0]->id, $data->collaborators[0]->id);
    $this->assertEquals($users[1]->id, $data->collaborators[1]->id);
  }

  public function testUpdate()
  {
    $this->setupData();
    // Collaborators
    $users = Woodling::savedList('User', 2);
    // Campaign to update
    $campaign = Woodling::saved('Campaign', [
      'account_id' => $this->testAccount->id,
      'user_id' => $this->testUser->id,
      'campaign_type_id' => $this->testCampaignType->id,
    ]);
    
    // Attach tags
    $campaign->tags()->save(new CampaignTag(['tag' => 'Tag 1']));
    $campaign->tags()->save(new CampaignTag(['tag' => 'Tag 2']));
    
    // Attach collaborators
    $campaign->collaborators()->sync([$users[0]->id, $users[1]->id]);

    // Change data
    $campaign->title = 'New title';
    $campaign->status = 0;
    $campaign->is_recurring = ! $campaign->is_recurring;
    $campaign->description = 'Updated description';
    $campaign->goals = 'Updated goals';
    $campaign->concept = 'Updated concept';
    $campaign->tags = [
      ['tag' => 'Updated tag 1'],
      ['tag' => 'Updated tag 2']
    ];
    $updatedUser = Woodling::saved('User');
    $campaign->collaborators = [
      $updatedUser->toArray()
    ];

    $response = $this->call('PUT', '/api/account/'. $this->testAccount->id .'/campaigns/'. $campaign->id, $campaign->toArray());
    $data = $this->assertResponse($response);
    $this->assertCampaign($campaign, $data);
    // Make sure new tags were attached
    $this->assertEquals('Updated tag 1', $data->tags[0]->tag);
    $this->assertEquals('Updated tag 2', $data->tags[1]->tag);
    // Make sure there is only the updated collaborator
    $this->assertEquals($updatedUser->id, $data->collaborators[0]->id);
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