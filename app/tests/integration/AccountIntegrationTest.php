<?php

/**
 * Integration test for Accounts
 * Runs tests against a sqllite database to make sure apis
 * are interacting with the database correctly and returning correct responses
 */
class AccountIntegrationTest extends TestCase {

	/**
	 * Test account id that gets seeded before each test
	 * @var integer
	 */
	protected $surgeId;

	/**
	 * Setup runs before each test method
	 */
	public function setUp()
	{
		parent::setUp();
		// Start off clean
		DB::table('accounts')->delete();
		// Seed accounts table
		$this->seed('AccountSeeder');
		$this->surgeId = Account::where('title', 'Surge')->pluck('id');
	}

	public function testIndex() 
	{
		$response = $this->call('GET', 'api/account');
		$this->assertResponseOk();
		$accounts = json_decode($response->getContent());
		$this->assertEquals('Surge', $accounts[0]->title);
	}

	public function testStore()
	{
		$response = $this->call('POST', 'api/account', array(
			'title' => 'New Account',
			'active' => 1
		));
		$account = json_decode($response->getContent());
		$this->assertEquals('New Account', $account->title);
		$this->assertNotEmpty($account->id);
	}

	public function testShow()
	{
		$response = $this->call('GET', 'api/account/'. $this->surgeId);
		$this->assertResponseOk();
		$account = json_decode($response->getContent());
		$this->assertEquals('Surge', $account->title);
	}

	public function testUpdate()
	{
		$response = $this->call('PUT', 'api/account/'. $this->surgeId, array('title' => 'Surge Foo'));
		$this->assertResponseOk();
		$account = json_decode($response->getContent());
		$this->assertEquals('Surge Foo', $account->title);
	}

	public function testDestroy()
	{
		// @todo: Implement soft delete?
		$response = $this->call('DELETE', 'api/account/'. $this->surgeId);
		$this->assertResponseOk();
		$response = json_decode($response->getContent());
		// @todo: Determine a common response to use here
		$this->assertEquals('OK', $response->success);
	}

}
