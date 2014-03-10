<?php

/**
 * Integration test for Accounts
 * Runs tests against a sqllite database to make sure apis
 * are interacting with the database correctly and returning correct responses
 */
class UserIntegrationTest extends TestCase {

	public function testIndexListsUsers()
	{
		// Get a listing of all the users in the system
		// Setup test users
		$this->setupTestUsers();
		// Setup test roles
		$this->setupTestRoles();
		// Attach roles
		// Give user 1 admin and editor role
		$this->setupAttachUserToRole(1, 1);
		$this->setupAttachUserToRole(1, 2);
		// Give user 2 editor role
		$this->setupAttachUserToRole(2, 2);
		// Make a call to the api
		$response = $this->call('GET', 'api/user');
		// Response 200
		$this->assertResponseOk();
		$users = json_decode($response->getContent());
		// Setup first user expectation with roles
		$expect = $this->getTestUsers(1);
		$expect['roles'] = array(
			array(
				'id' => 1,
				'name' => 'Admin'
			),
			array(
				'id' => 2,
				'name' => 'Editor'
			)
		);
		$this->assertUserFields($expect, $users[0]);
		// Setup second user expectation with roles
		$expect = $this->getTestUsers(2);
		$expect['roles'] = array(array(
			'id' => 2,
			'name' => 'Editor'
		));
		$this->assertUserFields($expect, $users[1]);
	}

	public function testStoreNewUser()
	{
		// Setup test roles
		$this->setupTestRoles();
		// User to create
		$data = $this->getTestUsers(1);
		// No password will be sent, the system should store user as unconfirmed and inactive
		// and send them a confirmation email to set thier own password
		// Unset fields that shouldn't be passed
		unset($data['password'], $data['password_confirmation'],
			$data['id'], $data['created_at'], $data['updated_at'], $data['confirmed']);
		// Add role
		$data['roles'] = array(array(
			'id' => 1,
			'name' => 'Admin'
		));
		$response = $this->call('POST', 'api/user', $data);
		$this->assertResponseOk();
		$user = json_decode($response->getContent());
		$expect = $this->getTestUsers(1);
		// Add role
		$expect['roles'] = array(array(
			'id' => 1,
			'name' => 'Admin'
		));
		// User should be unconfirmed
		$expect['confirmed'] = 0;
		// User should be inactive
		$expect['status'] = 0;
		// Empty account for now
		$expect['accounts'] = array();
		$this->assertUserFields($expect, $user);
	}

	public function testShow()
	{
		$this->setupTestUsers();
		$this->setupTestRoles();
		$this->setupAttachUserToRole(1, 1);
		$response = $this->call('GET', 'api/user/1');
		$this->assertResponseOk();
		$user = json_decode($response->getContent());
		$expect = $this->getTestUsers(1);
		$expect['roles'] = array(array(
			'id' => 1,
			'name' => 'Admin'
		));
		$this->assertUserFields($expect, $user);
	}

	public function testUpdate()
	{
		$this->setupTestUsers();
		$this->setupTestRoles();
		$this->setupAttachUserToRole(1, 1);
		$changes = array(
			'email' => 'jbizzay@mail.net',
			'first_name' => 'J',
			'last_name' => 'B',
			'confirmed' => 0,
			'roles' => array(0 => 2),
			'address' => '4321 Foobar Rd.',
			'address2' => '',
			'city' => 'Spangle',
			'state' => 'UT',
			'phone' => '5094321432',
			'status' => 0,
			'country' => 'Mexico'
		);
		// Update user
		$response = $this->call('PUT', 'api/user/1', $changes);
		$this->assertResponseOk();
		$user = json_decode($response->getContent());
		$expect = array_merge($this->getTestUsers(1), $changes);
		$expect['roles'] = array(array(
			'id' => 2,
			'name' => 'Editor'
		));
	}

	public function testUpdateShouldBeAbleToUpdateSingleField()
	{
		$this->setupTestUsers();
		$this->setupTestRoles();
		$expect = $this->getTestUsers(1);
		// Attempt to update only one field, other fields should stay unchanged
		$response = $this->call('PUT', 'api/user/1', array(
			'email' => 'jbizzay@mail.net'
		));
		$this->assertResponseOk();
		$user = json_decode($response->getContent());
		$expect['email'] = $expect['username'] = 'jbizzay@mail.net';
		$expect['roles'] = array();
		$this->assertUserFields($expect, $user);
	}

	public function testDestroy()
	{
		// @todo: Implement soft delete?
		$this->setupTestUsers();
		$this->setupTestRoles();
		$this->setupAttachUserToRole(1, 1);
		$response = $this->call('DELETE', 'api/user/1');
		$this->assertResponseOk();
		$response = json_decode($response->getContent());
		// @todo: Determine a common response to use here
		$this->assertEquals('OK', $response->success);
		$id = DB::table('users')->where('id', 1)->pluck('id');
		$this->assertEmpty($id);
	}

}
