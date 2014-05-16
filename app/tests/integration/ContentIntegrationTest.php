<?php

use Woodling\Woodling;

class ContentIntegrationTest extends TestCase {

  protected $testAccount, $testCampaign, $testCampaignType, $testContent, $testContentType, $testUser;

  public function assertContent($expected, $content)
  {
    foreach ([
      'title', 'body', 'buying_stage',
      'persona', 'secondary_buying_stage', 'secondary_persona',
      'concept', 'status', 'archived'
    ] as $field) {
      $this->assertEquals($expected->$field, $content->$field, 'Field: '. $field .' is not equal');
    }
  }

  public function assertContentComment($expected, $comment)
  {
    foreach ([
      'user_id', 'content_id', 'comment'
    ] as $field) {
      $this->assertEquals($expected->$field, $comment->$field, 'Field: '. $field .' is not equal');
    }
  }

  protected function setupContent()
  {
    // Setup data needed for content
    $this->testAccount = Woodling::saved('Account');
    $this->testUser = Woodling::saved('User');
    $this->testCampaignType = Woodling::saved('CampaignType');
    $this->testCampaign = Woodling::saved('Campaign', [
      'account_id' => $this->testAccount->id,
      'user_id' => $this->testUser->id,
      'campaign_type_id' => $this->testCampaignType->id
    ]);
    $this->testContentType = Woodling::saved('ContentType');
    $this->testContent = Woodling::saved('Content', [
      'user_id' => $this->testUser->id,
      'account_id' => $this->testAccount->id,
      'content_type_id' => $this->testContentType->id,
      'campaign_id' => $this->testCampaign->id,
    ]);
  }
  
  public function testIndex()
  {
    $account = Woodling::saved('Account');
    $type = Woodling::saved('ContentType');
    $user = Woodling::saved('User');
    $campaignType = Woodling::saved('CampaignType');
    $campaign = Woodling::saved('Campaign', [
      'account_id' => $account->id,
      'user_id' => $user->id,
      'campaign_type_id' => $campaignType->id
    ]);
    $content = Woodling::savedList('Content', 3, [
      'account_id' => $account->id,
      'content_type_id' => $type->id,
      'user_id' => $user->id,
      'campaign_id' => $campaign->id
    ]);
    $response = $this->call('GET', '/api/account/'. $account->id .'/content');
    $data = $this->assertResponse($response);
    $this->assertContent($content[0], $data[0]);
    $this->assertContent($content[1], $data[1]);
    $this->assertContent($content[2], $data[2]);
  }

  public function testPost()
  {
    $account = Woodling::saved('Account');
    $user = Woodling::saved('User');
    $campaignType = Woodling::saved('CampaignType');
    $campaign = Woodling::saved('Campaign', [
      'account_id' => $account->id,
      'user_id' => $user->id,
      'campaign_type_id' => $campaignType->id
    ]);
    $type = Woodling::saved('ContentType');
    $connection = Woodling::saved('Connection');
    $accountConnections = Woodling::savedList('AccountConnection', 2, [
      'account_id' => $account->id,
      'connection_id' => $connection->id,
    ]);
    $related = Woodling::savedList('Content', 2, [
      'user_id' => $user->id,
      'account_id' => $account->id,
    ]);
    $content = Woodling::retrieve('Content', [
      'user' => $user->toArray(),
      'content_type' => $type->toArray(),
      'campaign' => $campaign->toArray(),
      'tags' => [
        ['tag' => 'Tag 1'],
        ['tag' => 'Tag 2'],
        ['tag' => 'Tag 3']
      ],
      'account_connections' => [
        $accountConnections[0]->toArray(),
        $accountConnections[1]->toArray()
      ],
      'related' => [
        $related[0]->toArray(),
        $related[1]->toArray()
      ]
    ]);
    $response = $this->call('POST', '/api/account/'. $account->id .'/content', $content->toArray());
    $data = $this->assertResponse($response);
    $this->assertContent($content, $data);
    $this->assertEquals($user->id, $data->user->id);
    $this->assertEquals($type->id, $data->content_type->id);
    $this->assertEquals($campaign->id, $data->campaign->id);
    $this->assertEquals('Tag 1', $data->tags[0]->tag);
    $this->assertEquals('Tag 2', $data->tags[1]->tag);
    $this->assertEquals('Tag 3', $data->tags[2]->tag);
    $this->assertEquals($accountConnections[0]->id, $data->account_connections[0]->id);
    $this->assertEquals($accountConnections[1]->id, $data->account_connections[1]->id);
    $this->assertEquals($related[0]->id, $data->related[0]->id);
    $this->assertEquals($related[1]->id, $data->related[1]->id);
  }

  public function testUpdate()
  {
    // Setup data needed for content
    $account = Woodling::saved('Account');
    $user = Woodling::saved('User');
    $campaignType = Woodling::saved('CampaignType');
    $campaign = Woodling::saved('Campaign', [
      'account_id' => $account->id,
      'user_id' => $user->id,
      'campaign_type_id' => $campaignType->id
    ]);
    $type = Woodling::saved('ContentType');

    // Original content to update
    $content = Woodling::saved('Content', [
      'user_id' => $user->id,
      'account_id' => $account->id,
      'content_type_id' => $type->id,
      'campaign_id' => $campaign->id,
    ]);
    
    // Attach tags
    $tags = Woodling::savedList('ContentTag', 2, [
      'content_id' => $content->id
    ]);

    // Attach account connections
    $connection = Woodling::saved('Connection');
    $accountConnections = Woodling::savedList('AccountConnection', 2, [
      'account_id' => $account->id,
      'connection_id' => $connection->id,
    ]);
    foreach ($accountConnections as $connection) {
      $content->account_connections()->attach($connection->id);
    }
    
    // Attach related content
    $related = Woodling::savedList('Content', 2, [
      'user_id' => $user->id,
      'account_id' => $account->id,
    ]);
    foreach ($related as $relatedContent) {
      $content->related()->attach($relatedContent->id);
    }

    // Update data direct on content, this will be asserted
    $content->title = 'Updated title';
    $content->body = 'Updated body';
    $content->buying_stage = 3;
    $content->persona = 'New persona';
    $content->secondary_buying_stage = 4;
    $content->secondary_persona = 'New secondary persona';
    $content->concept = 'New concept';
    $content->status = $content->status + 1;
    $content->archived = ! $content->archived;

    // Setup data to PUT on the server
    $data = $content->toArray();
    
    // Update user
    $user = Woodling::saved('User');
    $data['user'] = $user->toArray();
    
    // Update content type
    $type = Woodling::saved('ContentType');
    $data['content_type'] = $type->toArray();

    // Update campaign
    $campaign = Woodling::saved('Campaign', [
      'account_id' => $account->id,
      'user_id' => $user->id,
      'campaign_type_id' => $campaignType->id
    ]);
    $data['campaign'] = $campaign->toArray();

    // Update tags
    $data['tags'] = [
      ['tag' => 'Updated Tag 1'],
      ['tag' => 'Updated Tag 2']
    ];

    // Update account connections
    $accountConnections = Woodling::savedList('AccountConnection', 2, [
      'account_id' => $account->id,
      'connection_id' => $connection->id,
    ]);
    $data['account_connections'] = [
      $accountConnections[0]->toArray(),
      $accountConnections[1]->toArray()
    ];

    // Update related content
    $related = Woodling::savedList('Content', 2, [
      'user_id' => $user->id,
      'account_id' => $account->id
    ]);
    $data['related'] = [
      $related[0]->toArray(),
      $related[1]->toArray()
    ];

    // Make call to server
    $response = $this->call('PUT', '/api/account/'. $account->id .'/content/'. $content->id, $data);
    $data = $this->assertResponse($response);
    $this->assertContent($content, $data);
    $this->assertEquals($user->id, $data->user->id);
    $this->assertEquals($type->id, $data->content_type->id);
    $this->assertEquals($campaign->id, $data->campaign->id);
    $this->assertEquals('Updated Tag 1', $data->tags[0]->tag);
    $this->assertEquals('Updated Tag 2', $data->tags[1]->tag);
    $this->assertEquals($accountConnections[0]->id, $data->account_connections[0]->id);
    $this->assertEquals($accountConnections[1]->id, $data->account_connections[1]->id);
    $this->assertEquals($related[0]->id, $data->related[0]->id);
    $this->assertEquals($related[1]->id, $data->related[1]->id);
  }

  public function testDestroy()
  {
    $this->setupContent();
    $response = $this->call('DELETE', '/api/account/'. $this->testAccount->id .'/content/'. $this->testContent->id);
    $data = $this->assertResponse($response);
  }

  public function testCommentsIndex()
  {
    $this->setupContent();
    // Save comments on test content
    $comments = Woodling::savedList('ContentComment', 3, [
      'user_id' => $this->testUser->id,
      'content_id' => $this->testContent->id
    ]);
    $response = $this->call('GET', '/api/account/'. $this->testAccount->id .'/content/'. $this->testContent->id .'/comments');
    $data = $this->assertResponse($response);
    $this->assertContentComment($comments[0], $data[0]);
    $this->assertContentComment($comments[1], $data[1]);
    $this->assertContentComment($comments[2], $data[2]);
  }

  public function testCommentsStore()
  {
    $this->setupContent();
    $comment = Woodling::retrieve('ContentComment', [
      'user_id' => $this->testUser->id,
      'content_id' => $this->testContent->id
    ]);
    // Add comment to content through api
    $response = $this->call('POST', '/api/account/'. $this->testAccount->id .'/content/'. $this->testContent->id .'/comments', $comment->toArray());
    $data = $this->assertResponse($response);
    // Make index call on content
    $response = $this->call('GET', '/api/account/'. $this->testAccount->id .'/content/'. $this->testContent->id .'/comments');
    $data = $this->assertResponse($response);
    $this->assertContentComment($comment, $data[0]);
  }

  public function testCollaboratorsIndex()
  {
    $this->setupContent();
    // Add collaborators to content
    $user1 = Woodling::saved('User');
    $user2 = Woodling::saved('User');
    $this->testContent->collaborators()->attach($user1->id);
    $this->testContent->collaborators()->attach($user2->id);
    $response = $this->call('GET', '/api/account/'. $this->testAccount->id .'/content/'. $this->testContent->id .'/collaborators');
    $data = $this->assertResponse($response);
    $this->assertEquals($user1->id, $data[0]->id);
    $this->assertEquals($user2->id, $data[1]->id);
  }

  public function testCollaboratorsStore()
  {
    $this->setupContent();
    // Collaborator to add to content
    $user1 = Woodling::saved('User');
    $params = ['user_id' => $user1->id];
    $response = $this->call('POST', '/api/account/'. $this->testAccount->id .'/content/'. $this->testContent->id .'/collaborators', $params);
    $data = $this->assertResponse($response);
    $this->assertEquals($user1->id, $data[0]->id);
  }

  public function testCollaboratorsDestroy()
  {
    $this->setupContent();
    // Add collaborator to content
    $user1 = Woodling::saved('User');
    $this->testContent->collaborators()->attach($user1->id);
    $response = $this->call('DELETE', '/api/account/'. $this->testAccount->id .'/content/'. $this->testContent->id .'/collaborators/'. $user1->id);
    $this->assertResponse($response);
    /*
    $this->assertEquals(200, $response->getStatusCode());
    $data = $response->getContent();
    $this->assertIsNotSet($data);
    */
  }

}