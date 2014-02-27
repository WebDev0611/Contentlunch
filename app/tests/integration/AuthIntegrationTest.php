<?php

/**
 * Integration test for Auth
 */
class AuthIntegrationTest extends TestCase {

	// test user
	protected $testUser;

	public function setUp()
	{
		parent::setUp();
		DB::table('users')->delete();
		// Add test user to db
		$user = new User;
		$user->username = 'test';
		$user->email = 'test@mail.net';
		$user->password = $user->password_confirmation = 'password';
		//$user->password = 'password';
		$user->created_at = $user->updated_at = time();
		$user->first_name = 'First';
		$user->last_name = 'Last';
		$user->confirmed = 1;
		$user->confirmation_code = '12345';
		$user->save();
		$this->testUser = $user;
	}

	public function testCurrentUserGuest()
	{
		// Call the service that returns the current loggedin user
		// Shouldn't be logged in yet
		$response = $this->call('GET', '/api/auth');
		$this->assertResponseOk();
		$user = json_decode($response->getContent());
		$this->assertEquals('guest', $user->name);
	}

	public function testUserCanLogin()
	{
		// Log the user in
		$response = $this->call('POST', '/api/auth', array(
			'email' => 'test@mail.net',
			'password' => 'password'
		));
		$this->assertResponseOk();
		$user = json_decode($response->getContent());
		$this->assertObjectNotHasAttribute('password', $user);
		$this->assertEquals('test@mail.net', $user->email);
	}

	public function testGetCurrentUserAuth()
	{
		// Log the user in
		$attempt = Confide::logAttempt(array(
			'email' => 'test@mail.net',
			'password' => 'password'
		));
		$user = Confide::user();
		$ctrl = new AuthController;
		$response = $ctrl->callAction('show_current', array($user->id));
		$data = json_decode($response);
		$this->assertEquals('test@mail.net', $user->email);
		$this->assertObjectNotHasAttribute('password', $user);
	}

}
