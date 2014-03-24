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

  protected function setupAttachAccountSubs($account_id, $sub_id) {

  }

  /**
   * Test data for roles
   */
  protected function getTestRoles($id = null) {
    $roles = array(
      1 => array(
        'id' => 1, 'name' => 'Admin', 'created_at' => $this->now, 'updated_at' => $this->now
      ),
      2 => array(
        'id' => 2, 'name' => 'Editor', 'created_at' => $this->now, 'updated_at' => $this->now
      )
    );
    if ($id) {
      return $roles[$id];
    }
    return $roles;
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
        'address' => '123 First Dr', 'address_2' => 'Apt K123', 'city' => 'Spokane', 'state' => 'WA',
        'phone' => '5091231234', 'status' => 1,
        'confirmation_code' => 12345, 'country' => 'US'
      ),
      2 => array(
        'id' => 2, 'username' => 'test2@mail.net', 'email' => 'test2@mail.net',
        'password' => Hash::make('password'), 'created_at' => $this->now, 'updated_at' => $this->now,
        'first_name' => 'First2', 'last_name' => 'Last2', 'confirmed' => 1,
        'address' => '91919 E Main st.', 'address_2' => 'Suite 5132', 'city' => 'Spangle', 'state' => 'OH',
        'phone' => '2069193818', 'status' => 1,
        'confirmation_code' => 54321, 'country' => 'Canada'
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
        'name' => 'SurgeForward',
        'address' => '123 S Main',
        'address_2' => 'St. 104',
        'city' => 'Seattle',
        'state' => 'WA',
        'phone' => '891-232-3113',
        //'subscription' => 2,
        'country' => 'US',
        'zipcode' => '99128',
        'email' => 'test@surge.com',
        //'licenses' => 20,
        'created_at' => $this->now,
        'updated_at' => $this->now,
        /*'expiration_date' => strtotime('+30 days'),
        'auto_renew' => true,
        'yearly_payment' => true,
        'payment_type' => 'CC',
        'token' => '1123312312313'
        */
      ),
      2 => array(
        'id' => 2,
        'title' => 'Test Account',
        'active' => 0,
        'name' => 'Testing',
        'address' => '321 S Main',
        'address_2' => 'St. 101',
        'city' => 'Spokane',
        'state' => 'WA',
        'phone' => '891-111-3113',
        //'subscription' => 5,
        'country' => 'US',
        'zipcode' => '99133',
        'email' => 'test2@surge.com',
        //'licenses' => 0,
        'created_at' => $this->now,
        'updated_at' => $this->now,
        /*'expiration_date' => strtotime('-30 days'),
        'auto_renew' => false,
        'yearly_payment' => false,
        'payment_type' => 'ACH',
        'token' => '1123313'
        */
      )
    );
    if ($id) {
      return $accounts[$id];
    }
    return $accounts;
  }

  protected function getTestSubscriptions() {
    $subs = array(
      1 => array(
        'id' => 1,
        'account_id' => 1,
        'auto_renew' => 1,
        'subscription_date' => $this->now,
        'licenses' => 20,
        'payment_type' => 'CC',
        'subscription' => 1,
        'token' => '4321',
        'yearly_payment' => 0,
        'created_at' => $this->now,
        'updated_at' => $this->now
      )
    );
    return $subs;
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

  protected function assertResponse($response, $fail = false)
  {
    // Is correct http status?
    $status = $fail ? 400 : 200;
    $this->assertEquals($status, $response->getStatusCode(), "Response: ". $response->getContent());
    // Response is json?
    $data = json_decode($response->getContent());
    $this->assertNotEmpty($data, "Response was not JSON");
    // Fail responses should contain error(s)
    if ($fail) {
      $this->assertTrue( ! empty($data->errors) || ! empty($data->error), "Fail response should contain error(s). Response: ". print_r($data, 1));
    }
    return $data;
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
    $this->assertEquals($expect['name'], $account->name, $err .' ->name');
    $this->assertEquals($expect['address'], $account->address, $err .' ->address');
    $this->assertEquals($expect['address_2'], $account->address_2, $err .' ->address_2');
    $this->assertEquals($expect['city'], $account->city, $err .' ->city');
    $this->assertEquals($expect['state'], $account->state, $err .' ->state');
    $this->assertEquals($expect['phone'], $account->phone, $err .' ->phone');
    $this->assertEquals($expect['country'], $account->country, $err .' ->country');
    $this->assertEquals($expect['zipcode'], $account->zipcode, $err .' ->zipcode');
    $this->assertEquals($expect['email'], $account->email, $err .' ->email');
    $this->assertNotEmpty($account->created_at, $err .' ->created_at');
    $this->assertNotEmpty($account->updated_at, $err .' ->updated_at');
    $count_users = DB::table('account_user')
      ->select(DB::raw("COUNT(*) as countusers"))
      ->where('account_id', $expect['id'])
      ->pluck('countusers');
    if ( ! $count_users) {
      $count_users = null;
    }
    $this->assertEquals($count_users, $account->count_users);
  }

  /**
   * Assert role fields
   * @param  array $expect Expected role
   * @param  object $role  Role object to check
   */
  protected function assertRoleFields($expect, $role) {
    $err = 'Failed assertion in role object: ';
    $this->assertEquals($expect['id'], $role->id, $err .' ->id');
    $this->assertEquals($expect['name'], $role->name, $err .' ->name');
    $this->assertNotEmpty($role->created_at, $err .' ->created_at');
    $this->assertNotEmpty($role->updated_at, $err .' ->updated_at');
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
    $this->assertEquals($expect['address'], $user->address, $err .' ->address');
    $this->assertEquals($expect['address_2'], $user->address_2, $err .' ->address_2');
    $this->assertEquals($expect['city'], $user->city, $err .' ->city');
    $this->assertEquals($expect['state'], $user->state, $err .' ->state');
    $this->assertEquals($expect['phone'], $user->phone, $err .' ->phone');
    $this->assertEquals($expect['status'], $user->status, $err .' ->status');
    $this->assertEquals($expect['country'], $user->country, $err .' ->country');
    // Make sure certain fields aren't set
    $this->assertObjectNotHasAttribute('password', $user, $err ." ->password shouldn't be set.");
    $this->assertObjectNotHasAttribute('password_confirmation', $user, $err ." ->password_confirmation shouldn't be set.");
    $this->assertObjectNotHasAttribute('confirmation_code', $user, $err ." ->confirmation_code shouldn't be set.");

    $roles = array();
    if ( ! empty($expect['roles'])) {
      foreach ($expect['roles'] as $key => $role) {
        $this->assertEquals($role['id'], $user->roles[$key]->id);
      }
    }
    //$this->assertEquals($roles, $user->roles, $err .' ->roles');

    // Should have an accounts property
    $this->assertObjectHasAttribute('accounts', $user);
    /*
    $accounts = array();
    if ( ! empty($expect['accounts'])) {
      foreach ($expect['accounts'] as $account) {
        $accounts[] = (object) $account;
      }
    }
    $this->assertEquals($accounts, $user->accounts, $err .' ->accounts');*/
  }

  protected function assertSubscription($expect, $sub)
  {
    // Fields that should match
    $match = array(
      'id', 'licenses', 'monthly_price', 'annual_discount', 'training', 'features'
    );
    foreach ($match as $field) {
      $this->assertEquals($expect->$field, $sub->$field, "Subscription field $field doesn't match");
    }
  }

}
