<?php

/**
 * Integration test for Accounts
 * Runs tests against a sqllite database to make sure apis
 * are interacting with the database correctly and returning correct responses
 */
class UserIntegrationTest extends TestCase {

	protected $now;

	/**
	 * Setup before all tests
	 */
	public static function setUpBeforeClass()
	{

	}

	/**
	 * Setup runs before each test method
	 */
	public function setUp()
	{
		parent::setUp();
		// Start off clean
		$this->setupCleanTables();
		$this->now = date('Y-m-d H:i:s');
	}

	/**
	 * Empty out test tables
	 */
	protected function setupCleanTables()
	{
		DB::table('assigned_roles')->delete();
		DB::table('users')->delete();
		DB::table('roles')->delete();
	}

	/**
	 * Seed test users in the db
	 */
	protected function setupTestUsers()
	{
		$users = $this->getTestUsers();
		DB::table('users')->insert($users[1]);
		DB::table('users')->insert($users[2]);
	}

	/**
	 * Seed test roles in the db
	 */
	protected function setupTestRoles()
	{
		$roles = $this->getTestRoles();
		DB::table('roles')->insert($roles[1]);
		DB::table('roles')->insert($roles[2]);
	}

	/**
	 * Attach a user to a role in the db
	 * @param  integer $user_id
	 * @param  integer $role_id
	 */
	protected function setupAttachUserToRole($user_id, $role_id) {
		DB::table('assigned_roles')->insert(array(
			'user_id' => $user_id, 'role_id' => $role_id
		));
	}

	/**
	 * Test data for roles
	 */
	protected function getTestRoles() {
		return array(
			1 => array(
				'id' => 1, 'name' => 'Admin', 'created_at' => $this->now, 'updated_at' => $this->now
			),
			2 => array(
				'id' => 2, 'name' => 'Editor', 'created_at' => $this->now, 'updated_at' => $this->now
			)
		);
	}

	/**
	 * Test data for users
	 */
	protected function getTestUsers() {
		return array(
			1 => array(
				'id' => 1, 'username' => 'test@mail.net', 'email' => 'test@mail.net',
				'password' => Hash::make('password'), 'created_at' => $this->now, 'updated_at' => $this->now,
				'first_name' => 'First', 'last_name' => 'Last', 'confirmed' => 1, 
				'confirmation_code' => 12345
			),
			2 => array(
				'id' => 2, 'username' => 'test2@mail.net', 'email' => 'test2@mail.net',
				'password' => Hash::make('password'), 'created_at' => $this->now, 'updated_at' => $this->now,
				'first_name' => 'First2', 'last_name' => 'Last2', 'confirmed' => 1, 
				'confirmation_code' => 54321
			)
		);
	}

	/**
	 * Assertion helper, check valid fields and match test data
	 * @param  array $testUser User to check against
	 * @param  object $user User object returned
	 * @param  array $fields Fields to check, or check all
	 */
	protected function assertUserFields($testUser, $user, $fields = array()) {
		$err = 'Failed assertion in User object: ';
		// Make sure required fields are set and match
		$this->assertEquals($testUser['id'], $user->id, $err .' ->id');
		$this->assertEquals($testUser['username'], $user->username, $err .' ->username');
		$this->assertEquals($testUser['email'], $user->email, $err .' ->email');
		// Username and email should always be the same
		$this->assertEquals($user->username, $user->email, $err .' ->username and ->email should match.');
		$this->assertNotEmpty($user->created_at, $err .' ->created_at');
		$this->assertNotEmpty($user->updated_at, $err .' ->updated_at');
		$this->assertEquals($testUser['first_name'], $user->first_name, $err .' ->first_name');
		$this->assertEquals($testUser['last_name'], $user->last_name, $err .' ->last_name');
		$this->assertEquals($testUser['confirmed'], $user->confirmed, $err .' ->confirmed');
		// Make sure certain fields aren't set
		$this->assertObjectNotHasAttribute('password', $user, $err ." ->password shouldn't be set.");
		$this->assertObjectNotHasAttribute('password_confirmation', $user, $err ." ->password_confirmation shouldn't be set.");
		$this->assertObjectNotHasAttribute('confirmation_code', $user, $err ." ->confirmation_code shouldn't be set.");

		$this->assertEquals((object) $testUser['roles'], $user->roles, $err .' ->roles');
	}


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
		// Get data to match against
		$testUsers = $this->getTestUsers();
		// Setup first user expectation with roles
		$expect = $testUsers[1];
		$expect['roles'] = array(1 => 'Admin', 2 => 'Editor');
		$this->assertUserFields($expect, $users[0]);
		// Setup second user expectation with roles
		$expect = $testUsers[2];
		$expect['roles'] = array(2 => 'Editor');
		$this->assertUserFields($expect, $users[1]);
	}

	public function testStoreNewUser()
	{
		// Setup test roles
		$this->setupTestRoles();
		// Create a new user
		$testUsers = $this->getTestUsers();
		$data = $testUsers[1];
		// Setup password
		$data['password_confirmation'] = $data['password'] = 'password';
		// Add role
		$data['roles'] = array(0 => 1);
		// Don't need to post username, timestamps
		unset($data['username'], $data['created_at'], $data['updated_at']);
		$response = $this->call('POST', 'api/user', $data);
		$this->assertResponseOk();
		$user = json_decode($response->getContent());
		$expect = $testUsers[1];
		// Add role
		$expect['roles'] = array(1 => 'Admin');
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
		$testUsers = $this->getTestUsers();
		$expect = $testUsers[1];
		$expect['roles'] = array(1 => 'Admin');
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
			'roles' => array(0 => 2)
		);
		// Update user
		$response = $this->call('PUT', 'api/user/1', $changes);
		$this->assertResponseOk();
		$user = json_decode($response->getContent());
		$testUsers = $this->getTestUsers();
		$expect = array_merge($testUsers[1], $changes);
		$expect['roles'] = array(2 => 'Editor');
	}

	public function testUpdateShouldBeAbleToUpdateSingleField()
	{
		$this->setupTestUsers();
		$this->setupTestRoles();
		$testUsers = $this->getTestUsers();
		$expect = $testUsers[1];
		// Attempt to update only one field, other fields should stay unchanged
		$response = $this->call('PUT', 'api/user/1', array(
			'email' => 'jbizzay@mail.net'
		));
		$this->assertResponseOk();
		$user = json_decode($response->getContent());
		$expect['email'] = $expect['username'] = 'jbizzay@mail.net';
		$expect['roles'] = array();
	}

	public function testDestroy()
	{
		// @todo: Implement soft delete?
		// 
		$this->setupTestUsers();
		$this->setupTestRoles();
		$response = $this->call('DELETE', 'api/user/1');
		$this->assertResponseOk();
		$response = json_decode($response->getContent());
		// @todo: Determine a common response to use here
		$this->assertEquals('OK', $response->success);
		$id = DB::table('users')->where('id', 1)->pluck('id');
		$this->assertEmpty($id);
	}

}
