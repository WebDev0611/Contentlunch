<?php

use Woodling\Woodling;

/**
 * Integration test for Auth
 */
class AuthIntegrationTest extends TestCase {

	public function testCurrentUserGuest()
	{
		// Call the service that returns the current loggedin user
		// Shouldn't be logged in yet
		$response = $this->call('GET', '/api/auth');
		$data = $this->assertResponse($response);
		$this->assertEquals('guest', $data->username);
	}

	public function testLoginSuccessReturnsUserObject()
	{
		$user = Woodling::saved('User');
		$response = $this->call('POST', '/api/auth', array(
			'email' => $user->email,
			'password' => 'password'
		));
		$data = $this->assertResponse($response);
		$this->assertUser($user, $data);
	}

	public function testUserCanLoginAndGetCurrentUserAuth()
	{
		$user = Woodling::saved('User');
		$response = $this->call('POST', '/api/auth', array(
			'email' => $user->email,
			'password' => 'password'
		));
		// Get current logged in user
		$response = $this->call('GET', '/api/auth');
		$data = $this->assertResponse($response);
		$this->assertUser($user, $data);
	}

	public function testLoginFailureReturns401()
	{
		$response = $this->call('POST', '/api/auth', array(
			'email' => 'none@mail.net',
			'password' => 'foobar'
		));
		$data = $this->assertResponse($response, true, 401);
		$this->assertContains('Incorrect', $data->errors[0]);
	}

	public function testUserCanLogout()
	{
		$user = Woodling::saved('User');
		$response = $this->call('POST', '/api/auth', array(
			'email' => $user->email,
			'password' => 'password'
		));
		$data = $this->assertResponse($response);
		$response = $this->call('GET', '/api/auth/logout');
		// Should be guest
		$response = $this->call('GET', '/api/auth');
		$data = $this->assertResponse($response);
		$this->assertEquals('guest', $data->username);
	}

	public function testInactiveUserCannotLogin()
	{
		$user = Woodling::saved('User', array(
			'status' => 0
		));
		$response = $this->call('POST', '/api/auth', array(
			'email' => $user->email,
			'password' => 'password'
		));
		$data = $this->assertResponse($response, true, 401);
		$this->assertContains('inactive', $data->errors[0]);
	}

}
