<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase {

  protected $now;

  /**
   * Setup before all tests
   */
  public static function setUpBeforeClass()
  {

  }

  /**
   * Setup runs before each individual test
   */
  public function setUp()
  {
    parent::setUp();
    $this->prepareForTests();
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
    DB::table('accounts')->delete();
  }

  /**
   * Seed test accounts in the db
   */
  protected function setupTestAccounts()
  {
    $accounts = $this->getTestAccounts();
    foreach ($accounts as $account) {
      DB::table('accounts')->insert($account);
    }
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
  protected function getTestUsers($id = null) {
    $users = array(
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
    if ($id) {
      return $users[$id];
    }
    return $users;
  }

  protected function getTestAccounts($id = null)
  {
    $accounts = array(
      1 => array(
        'id' => 1,
        'title' => 'Surge',
        'active' => 1,
        'created_at' => $this->now,
        'updated_at' => $this->now
      ),
      2 => array(
        'id' => 2,
        'title' => 'Test Account',
        'active' => 0,
        'created_at' => $this->now,
        'updated_at' => $this->now
      )
    );
    if ($id) {
      return $accounts[$id];
    }
    return $accounts;
  }


	/**
	 * Creates the application.
	 *
	 * @return \Symfony\Component\HttpKernel\HttpKernelInterface
	 */
	public function createApplication()
	{
		$unitTesting = true;

		$testEnvironment = 'testing';

		return require __DIR__.'/../../bootstrap/start.php';
	}

  /**
   * Migrate the database and set the mailer to pretend
   */
  protected function prepareForTests()
  {
    Artisan::call('migrate');
    Mail::pretend(true);
  }

  /**
   * Assertion helper, check valid fields and match test data for accounts
   * @param  array $expect Expected account
   * @param  object $account Account object returned
   */
  protected function assertAccountFields($expect, $account)
  {
    $err = 'Failed assertion in account object: ';
    $this->assertEquals($expect['id'], $account->id, $err .' ->id');
    $this->assertEquals($expect['title'], $account->title, $err .' ->title');
    $this->assertEquals($expect['active'], $account->active, $err .' ->active');
    $this->assertNotEmpty($account->created_at, $err .' ->created_at');
    $this->assertNotEmpty($account->updated_at, $err .' ->updated_at');
  }

  /**
   * Assertion helper, check valid fields and match test data for users
   * @param  array $expect Expected user
   * @param  object $user User object returned
   */
  protected function assertUserFields($expect, $user)
  {
    $err = 'Failed assertion in User object: ';
    // Make sure required fields are set and match
    $this->assertEquals($expect['id'], $user->id, $err .' ->id');
    $this->assertEquals($expect['username'], $user->username, $err .' ->username');
    $this->assertEquals($expect['email'], $user->email, $err .' ->email');
    // Username and email should always be the same
    $this->assertEquals($user->username, $user->email, $err .' ->username and ->email should match.');
    $this->assertNotEmpty($user->created_at, $err .' ->created_at');
    $this->assertNotEmpty($user->updated_at, $err .' ->updated_at');
    $this->assertEquals($expect['first_name'], $user->first_name, $err .' ->first_name');
    $this->assertEquals($expect['last_name'], $user->last_name, $err .' ->last_name');
    $this->assertEquals($expect['confirmed'], $user->confirmed, $err .' ->confirmed');
    // Make sure certain fields aren't set
    $this->assertObjectNotHasAttribute('password', $user, $err ." ->password shouldn't be set.");
    $this->assertObjectNotHasAttribute('password_confirmation', $user, $err ." ->password_confirmation shouldn't be set.");
    $this->assertObjectNotHasAttribute('confirmation_code', $user, $err ." ->confirmation_code shouldn't be set.");

    $roles = empty($expect['roles']) ? array(): (object) $expect['roles'];
    $this->assertEquals($roles, $user->roles, $err .' ->roles');
  }

}
