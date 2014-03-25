<?php

use Woodling\Woodling;

class UserIntegrationTest extends TestCase {

	public function testGetUsers()
	{
		$users = Woodling::savedList('User', 5);
		$response = $this->call('GET', '/api/user');
		$data = $this->assertResponse($response);
		foreach ($users as $key => $user) {
			$this->assertUser($user, $data[$key]);
		}
	}

	public function testStoreNewUserReturnsUserObject()
	{
		$user = Woodling::retrieve('User', array(
			// Creating a new user, they are forced to unconfirmed, and 0 status
			'confirmed' => 0,
			'status' => 0
		));
		$response = $this->call('POST', '/api/user', $user->toArray());
		$data = $this->assertResponse($response);
		$user->id = $data->id;
		$this->assertUser($user, $data);
	}

	public function testStoreNewUserValidationFailure()
	{
		// Passwords don't match
		$response = $this->call('POST', '/api/user', array(
			'email' => 'foo@bar.net',
			'username' => 'foo@bar.net',
			'password' => 'alpha',
			'password_confirmation' => 'beta'
		));
		$data = $this->assertResponse($response, true);
		$this->assertContains('password', $data->errors[0]);
	}

	public function testStoreNewUserEmailExists()
	{
		$user = Woodling::saved('User');
		$response = $this->call('POST', '/api/user', array(
			'email' => $user->email,
			'username' => 'foobar123',
			'password' => 'password',
			'password_confirmation' => 'password'
		));
		$data = $this->assertResponse($response, true);
		$this->assertContains('used', $data->errors[0]);
	}

	public function testShowReturnsUserRecord()
	{
		$user = Woodling::saved('User');
		$response = $this->call('GET', '/api/user/'. $user->id);
		$data = $this->assertResponse($response);
		$this->assertUser($user, $data);
	}

	public function testShowNonExistantUserReturnsError()
	{
		$response = $this->call('GET', '/api/user/99999');
		$data = $this->assertResponse($response, true);
		$this->assertContains('found', $data->errors[0]);
	}

	public function testUpdateUserRecordReturnsUserObject()
	{
		$user = Woodling::saved('User');
		$changed = Woodling::retrieve('User', array(
			'id' => $user->id
		));
		$response = $this->call('PUT', '/api/user/'. $user->id, $changed->toArray());
		$data = $this->assertResponse($response);
		$this->assertUser($changed, $data);
	}

	public function testUpdateUserPassword()
	{
		$user = Woodling::saved('User');
		$response = $this->call('PUT', '/api/user/'. $user->id, array(
			'password' => 'foobar123',
			'password_confirmation' => 'foobar123'
		));
		$this->assertResponse($response);
		// Login with new password
		$response = $this->call('POST', '/api/auth', array(
			'email' => $user->email,
			'password' => 'foobar123'
		));
		$data = $this->assertResponse($response);
		$this->assertUser($user, $data);
	}

	public function testUpdateNonExistantUserReturnsError()
	{
		$response = $this->call('PUT', '/api/user/9999', array(
			'email' => 'foobar@mail.net'
		));
		$data = $this->assertResponse($response, true);
		$this->assertContains('found', $data->errors[0]);
	}

	public function testUpdateEmailAlreadyExistsReturnsError()
	{
		$users = Woodling::savedList('User', 2);
		$response = $this->call('PUT', '/api/user/'. $users[1]->id, array(
			'email' => $users[0]->email,
			'username' => $users[1]->username
		));
		$data = $this->assertResponse($response, true);
		$this->assertContains('taken', $data->errors[0]);
	}

	public function testDeleteUserReturnsSuccess()
	{
		$user = Woodling::saved('User');
		$response = $this->call('DELETE', '/api/user/'. $user->id);
		$data = $this->assertResponse($response);
		// Soft deleted
		$deleted = DB::table('users')->where('id', $user->id)->pluck('deleted_at');
		$this->assertNotEmpty($deleted);
	}

	public function testConfirmUserWithConfirmationCode()
	{
		$user = Woodling::saved('User', array(
			'confirmed' => 0
		));
		$code = DB::table('users')->where('id', $user->id)->pluck('confirmation_code');
		$response = $this->call('POST', '/api/auth/confirm', array(
			'code' => $code
		));
		$data = $this->assertResponse($response);
		$confirmed = DB::table('users')->where('id', $user->id)->pluck('confirmed');
		$this->assertEquals(1, $confirmed);
	}

	public function testConfirmUserWithInvalidConfirmationCode()
	{
		$user = Woodling::saved('User', array(
			'confirmed' => 0
		));
		$response = $this->call('POST', '/api/auth/confirm', array(
			'code' => '9999'
		));
		$data = $this->assertResponse($response, true);
		$this->assertContains('code', $data->errors[0]);
		$confirmed = DB::table('users')->where('id', $user->id)->pluck('confirmed');
		$this->assertEquals(0, $confirmed);
	}

	public function testForgotPasswordResetPasswordLogin()
	{
		$user = Woodling::saved('User');
		$response = $this->call('POST', '/api/auth/forgot_password', array(
			'email' => $user->email
		));
		$data = $this->assertResponse($response);
		$token = DB::table('password_reminders')->where('email', $user->email)->pluck('token');
		$response = $this->call('POST', '/api/auth/reset_password', array(
			'token' => $token,
			'password' => 'newpassword2',
			'password_confirmation' => 'newpassword2'
		));
		$this->assertResponse($response);
		$response = $this->call('POST', '/api/auth', array(
			'email' => $user->email,
			'password' => 'newpassword2'
		));
		$data = $this->assertResponse($response);
		$this->assertUser($user, $data);
	}

	public function testForgotPasswordInvalidEmail()
	{
		$response = $this->call('POST', '/api/auth/forgot_password', array(
			'email' => 'foobar@mail.net'
		));
		$data = $this->assertResponse($response, true);
		$this->assertContains('found', $data->errors[0]);
	}

	public function testResetPasswordInvalidToken()
	{
		$user = Woodling::saved('User');
		$response = $this->call('POST', '/api/auth/forgot_password', array(
			'email' => $user->email
		));
		$data = $this->assertResponse($response);
		$response = $this->call('POST', '/api/auth/reset_password', array(
			'token' => 999,
			'password' => 'newpassword',
			'password_confirmation' => 'newpassword'
		));
		$data = $this->assertResponse($response, true);
		$this->assertContains('password', $data->errors[0]);
	}

	public function testResetPasswordInvalidPassword()
	{
		$user = Woodling::saved('User');
		$response = $this->call('POST', '/api/auth/forgot_password', array(
			'email' => $user->email
		));
		$data = $this->assertResponse($response);
		$token = DB::table('password_reminders')->where('email', $user->email)->pluck('token');
		$response = $this->call('POST', '/api/auth/reset_password', array(
			'token' => $token,
			'password' => 'newpassword',
			'password_confirmation' => 'foobar'
		));
		$data = $this->assertResponse($response, true);
		$this->assertContains('password', $data->errors[0]);
	}

}
