<?php

use Woodling\Woodling;

class ContentIntegrationTest extends TestCase {

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
      ]
    ]);
    $response = $this->call('POST', '/api/account/'. $account->id .'/content', $content->toArray());
    $data = $this->assertResponse($response);
    $this->assertContent($content, $data);
    print_r($data);
    $this->assertEquals($user->id, $data->user->id);
    $this->assertEquals($type->id, $data->content_type->id);
    $this->assertEquals($campaign->id, $data->campaign->id);
    $this->assertEquals('Tag 1', $data->tags[0]->tag);
    $this->assertEquals('Tag 2', $data->tags[1]->tag);
    $this->assertEquals('Tag 3', $data->tags[2]->tag);
    $this->assertEquals($accountConnections[0]->id, $data->account_connections[0]->id);
    $this->assertEquals($accountConnections[1]->id, $data->account_connections[1]->id);
  }

}