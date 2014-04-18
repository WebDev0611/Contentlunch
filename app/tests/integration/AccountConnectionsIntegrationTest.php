<?php

use Woodling\Woodling;

class AccountConnectionsIntegrationTest extends TestCase {

  public function assertAccountConnection($expected, $connection)
  {
    if ( ! empty($expected->id)) {
      $expected = $expected->toArray();
      $expected = (object) $expected;
    }
    foreach ([
      'account_id', 'name', 'status', 'type', 'settings'
    ] as $field) {
      if (is_object($connection->$field)) {
        $connection->$field = (array) $connection->$field;
      }
      $this->assertEquals($expected->$field, $connection->$field);
    }
  }

  public function testGetConnections()
  {
    $account = Woodling::saved('Account');
    $connections = Woodling::savedList('AccountConnection', 2, array(
      'account_id' => $account->id
    ));
    $response = $this->call('GET', '/api/account/'. $account->id .'/connections');
    $data = $this->assertResponse($response);
    foreach ($connections as $key => $connection) {
      $this->assertAccountConnection($connection, $data[$key]);
    }
  }

  public function testGetConnectionsContent()
  {
    $account = Woodling::saved('Account');
    $seoConnection = Woodling::saved('AccountConnection', array(
      'account_id' => $account->id,
      'type' => 'seo'
    ));
    $connections = Woodling::savedList('AccountConnection', 2, array(
      'account_id' => $account->id,
      'type' => 'content'
    ));
    $response = $this->call('GET', '/api/account/'. $account->id .'/connections?type=content');
    $data = $this->assertResponse($response);
    $this->assertCount(2, $data);
    foreach ($connections as $key => $connection) {
      $this->assertAccountConnection($connection, $data[$key]);
    }
  }

  public function testCreateConnection()
  {
    $account = Woodling::saved('Account');
    $connection = Woodling::retrieve('AccountConnection');
    $response = $this->call('POST', '/api/account/'. $account->id .'/connections', $connection->toArray());
    $data = $this->assertResponse($response);
    $connection->account_id = $account->id;
    $connection->id = $data->id;
    $this->assertAccountConnection($connection, $data);
  }

  public function testUpdateConnection()
  {
    $account = Woodling::saved('Account');
    $connection = Woodling::saved('AccountConnection', array(
      'account_id' => $account->id
    ));
    $connection->name = 'Foobar';
    $connection->status = 0;
    $connection->type = 'content';
    $connection->settings = array('url' => 'http://foobar.net');
    $response = $this->call('PUT', '/api/account/'. $account->id .'/connections/'. $connection->id, $connection->toArray());
    $data = $this->assertResponse($response);
    $this->assertAccountConnection($connection, $data);
  }

  public function testDeleteConnection()
  {
    $account = Woodling::saved('Account');
    $connection = Woodling::saved('AccountConnection', array(
      'account_id' => $account->id
    ));
    $response = $this->call('DELETE', '/api/account/'. $account->id .'/connections/'. $connection->id);
    $data = $this->assertResponse($response);
  }

}
