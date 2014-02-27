<?php

/**
 * Integration test for Accounts
 * Runs tests against a sqllite database to make sure apis
 * are interacting with the database correctly and returning correct responses
 */
class UserIntegrationTest extends TestCase {

	/**
	 * Test account id that gets seeded before each test
	 * @var integer
	 */
	protected $user;

	/**
	 * Setup runs before each test method
	 */
	public function setUp()
	{
		parent::setUp();
		// Start off clean
		DB::table('users')->delete();
		// Create test user
		$user = new User;
		$user->username = 'test';
		$user->email = 'test@mail.net';
		$user->password = $user->password_confirmation = 'password';
		$user->created_at = $user->updated_at = time();
		$user->first_name = 'First';
		$user->last_name = 'Last';
		$user->confirmed = 1;
		$user->confirmation_code = '12345';
		$user->save();
		$this->user = $user;
	}

	public function testIndex() 
	{
		// Get a listing of all the users in the system
		$response = $this->call('GET', 'api/user');
		$this->assertResponseOk();
		$users = json_decode($response->getContent());
		$this->assertEquals('test', $users[0]->username);
		$this->assertEquals('First', $users[0]->first_name);
		$this->assertEquals('Last', $users[0]->last_name);
		$this->assertEquals(1, $users[0]->confirmed);
		$this->assertEquals($this->user->id, $users[0]->id);
	}

	public function testStore()
	{
		// Create a new user
		$response = $this->call('POST', 'api/user', array(
			'email' => 'foobar@mail.net',
			'password' => 'password2',
			'password_confirmation' => 'password2',
			'first_name' => 'Foo',
			'last_name' => 'Bar',
			'confirmed' => 1
		));
		$user = json_decode($response->getContent());
		$this->assertEquals('foobar@mail.net', $user->username);
		$this->assertEquals('foobar@mail.net', $user->email);
		$this->assertEquals('Foo', $user->first_name);
		$this->assertEquals('Bar', $user->last_name);
		$this->assertEquals(1, $user->confirmed);
		$this->assertNotEmpty($user->id);
	}

	public function testShow()
	{
		$response = $this->call('GET', 'api/user/'. $this->user->id);
		$this->assertResponseOk();
		$user = json_decode($response->getContent());
		$this->assertEquals('test', $user->username);
		$this->assertEquals('First', $user->first_name);
		$this->assertEquals('Last', $user->last_name);
		$this->assertEquals(1, $user->confirmed);
		$this->assertEquals($this->user->id, $user->id);
		// Shouldn't expose password info
		$this->assertObjectNotHasAttribute('confirmation_code', $user);
		$this->assertObjectNotHasAttribute('password', $user);
		$this->assertObjectNotHasAttribute('password_confirmation', $user);
	}

	public function testUpdate()
	{
		// Update user
		$response = $this->call('PUT', 'api/user/'. $this->user->id, array(
			'username' => 'jbizzay',
			'first_name' => 'J',
			'last_name' => 'Bizzay',
			'confirmed' => 0,
		));
		$this->assertResponseOk();
		$user = json_decode($response->getContent());
		$this->assertEquals('jbizzay', $user->username);
		$this->assertEquals('J', $user->first_name);
		$this->assertEquals('Bizzay', $user->last_name);
		$this->assertEquals(0, $user->confirmed);
	}

	public function testDestroy()
	{
		// @todo: Implement soft delete?
		$response = $this->call('DELETE', 'api/user/'. $this->user->id);
		$this->assertResponseOk();
		$response = json_decode($response->getContent());
		// @todo: Determine a common response to use here
		$this->assertEquals('OK', $response->success);
		$id = DB::table('users')->where('id', $this->user->id)->pluck('id');
		$this->assertEmpty($id);
	}

}
