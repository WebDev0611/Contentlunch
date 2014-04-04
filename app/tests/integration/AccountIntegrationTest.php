<?php

use Woodling\Woodling;

class AccountIntegrationTest extends TestCase {

	public function testGetAllAccounts()
	{
		$accounts = Woodling::savedList('Account', 3);
		$response = $this->call('GET', '/api/account');
		$data = $this->assertResponse($response);
		foreach ($accounts as $key => $account) {
			$this->assertAccount($account, $data[$key]);
		}
	}

	public function testCreateNewAccount()
	{
		// Hacky... save the site admin role for the new account user
		Woodling::saved('Role', array(
			'name' => 'Site Admin'
		));
		$account = Woodling::retrieve('Account', array(
			'payment_info' => array(
				'name' => 'Foobar',
				'last4' => 1234
			)
		));
		$response = $this->call('POST', '/api/account', $account->toArray());
		$data = $this->assertResponse($response);
		$account->id = $data->id;
		$this->assertAccount($account, $data);
		$this->assertEquals('Foobar', $data->payment_info->name);
		$this->assertEquals('1234', $data->payment_info->last4);
	}

	public function testCreateNewAccountFailValidationReturnsError()
	{
		$account = Woodling::retrieve('Account');
		// Title is required
		$account->title = '';
		$response = $this->call('POST', '/api/account', $account->toArray());
		$data = $this->assertResponse($response, true);
		$this->assertContains('required', $data->errors[0]);
	}

	public function testGetAccountRecord()
	{
		$accounts = Woodling::savedList('Account', 3);
		$response = $this->call('GET', '/api/account/'. $accounts[0]->id);
		$data = $this->assertResponse($response);
		$this->assertAccount($accounts[0], $data);
	}

	public function testUpdateAccountReturnsAccountObject()
	{
		$account = Woodling::saved('Account');
		$changed = Woodling::retrieve('Account', array(
			'id' => $account->id,
			'payment_info' => array(
				'name' => 'Foobar',
				'last4' => 1234,
			)
		));
		$response = $this->call('PUT', '/api/account/'. $account->id, $changed->toArray());
		$data = $this->assertResponse($response);
		$this->assertAccount($changed, $data);
		$this->assertEquals('Foobar', $data->payment_info->name);
		$this->assertEquals('1234', $data->payment_info->last4);
	}

	public function testUpdateAccountFailValidationReturnsError()
	{
		$accounts = Woodling::savedList('Account', 3);
		$changes = Woodling::retrieve('Account', array(
			// Titles should be unique
			'title' => $accounts[0]->title
		));
		$response = $this->call('PUT', '/api/account/'. $accounts[1]->id, $changes->toArray());
		$data = $this->assertResponse($response, true);
		$this->assertContains('taken', $data->errors[0]);
	}

	public function testDeleteAccount()
	{
		$account = Woodling::saved('Account');
		$response = $this->call('DELETE', '/api/account/'. $account->id);
		$data = $this->assertResponse($response);
		$id = DB::table('accounts')->where('id', $account->id)->pluck('id');
		$this->assertEmpty($id);
	}

}
