<?php

use Woodling\Woodling;

class AccountContentSettingsIntegrationTest extends TestCase {

  protected function assertAccountContentSettings($expect, $account)
  {
    if ( ! empty($expect->id)) {
      $expect = $expect->toArray();
      $expect = (object) $expect;
    }
    // Fields that should match
    $match = array(
      'account_id', 'include_name', 'allow_edit_date', 'keyword_tags',
      'publishing_guidelines', 'personas'
    );
    foreach ($match as $field) {
      if (is_object($account->$field)) {
        $account->$field = (array) $account->$field;
      }
      $this->assertEquals($expect->$field, $account->$field, "Account Content Settings field $field doesn't match.");
    }
    // Fields that should be set
    $set = array(
      'id', 'created_at', 'updated_at', //'expiration_date'
    );
    foreach ($set as $field) {
      $this->assertNotEmpty($account->$field, "Account field $field should not be empty");
    }
  }

  public function testGetSettings()
  {
    $account = Woodling::saved('Account');
    $settings = Woodling::saved('AccountContentSettings', array(
      'account_id' => $account->id
    ));
    $response = $this->call('GET', '/api/account/'. $account->id .'/content-settings');
    $data = $this->assertResponse($response);
    $this->assertAccountContentSettings($settings, $data);
  }

  public function testGetNoSettingsReturnsEmpty()
  {
    $account = Woodling::saved('Account');
    $response = $this->call('GET', '/api/account/'. $account->id .'/content-settings');
    $this->assertResponseOK();
    $this->assertEmpty($response->getContent());
  }

  public function testSaveSettings()
  {
    $account = Woodling::saved('Account');
    $settings = Woodling::retrieve('AccountContentSettings', array(
      'account_id' => $account->id
    ));
    $response = $this->call('PUT', '/api/account/'. $account->id .'/content-settings', $settings->toArray());
    $data = $this->assertResponse($response);
    $this->assertAccountContentSettings($settings, $data);
  }

  public function testUpdateSettings()
  {
    $account = Woodling::saved('Account');
    $settings = Woodling::saved('AccountContentSettings', array(
      'account_id' => $account->id
    ));
    $settings->include_name = array('changed' => 123);

    $response = $this->call('PUT', '/api/account/'. $account->id .'/content-settings', $settings->toArray());
    $data = $this->assertResponse($response);
    $this->assertAccountContentSettings($settings, $data);
  }

  public function testSaveSettingsMissingFieldReturnsError()
  {
    $settings = Woodling::retrieve('AccountContentSettings');
    $response = $this->call('PUT', '/api/account/999/content-settings', $settings->toArray());
    $data = $this->assertResponse($response, true);
    $this->assertContains('account', $data->errors[0]);
  }

}
