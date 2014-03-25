<?php

use Woodling\Woodling;

class AccountUserIntegrationTest extends TestCase {

  public function testAddUserToAccountReturnsSuccess()
  {
    $account = Woodling::saved('Account');
    $user = Woodling::saved('User');
    $response = $this->call('POST', '/api/account/'. $account->id .'/add_user', array(
      'user_id' => $user->id
    ));
    $data = $this->assertResponse($response);
    $id = DB::table('account_user')
      ->where('user_id', $user->id)->where('account_id', $account->id)
      ->pluck('id');
    $this->assertNotEmpty($id);
  }

  public function testGetAccountUsers()
  {
    $accounts = Woodling::savedList('Account', 5);
    $users = Woodling::savedList('User', 3);
    foreach ($users as $user) {
      Woodling::saved('AccountUser', array(
        'user_id' => $user->id,
        'account_id' => $accounts[1]->id
      ));
    }
    $response = $this->call('GET', '/api/account/'. $accounts[1]->id .'/users');
    $data = $this->assertResponse($response);
    foreach ($users as $key => $user) {
      $this->assertUser($user, $data[$key]);
    }
  }

}
