<?php

class IntegrationTestCase extends TestCase {

	/**
	 * Setup the application
	 * Use testing environment, which is configed with a sqllite database
	 * This will be called before each test, similar to setting up a request
	 * @todo : Run sqlite in memory for faster execution
	 */
	public function createApplication()
	{
		$unitTesting = true;
		$testEnvironment = 'testing';
		$app = require __DIR__ .'/../../../bootstrap/start.php';
		if ( ! $app->runningUnitTests()) {
			throw new Exception("Not in testing environment");
		}
		Artisan::call('migrate');
		return $app;
	}

}
