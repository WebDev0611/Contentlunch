<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class TestCommand extends Command {

	protected $name = 'cl:test';

	protected $description = 'Command description.';

	public function __construct()
	{
		parent::__construct();
	}

	public function fire()
	{
		$env = App::environment();
		echo "Running the test in the '$env' environment";
	}

}
