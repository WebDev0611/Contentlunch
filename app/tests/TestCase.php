<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase {

  public function setUp(){
    parent::setUp();
    $this->prepareForTests();
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

}
