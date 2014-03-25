<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class DeployCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'deploy:test';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Deploy to test server.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
    echo "Running deploy:test command";
    $token = 'qoOqob0jl9uXbXkkcaClq8wAdlabxNndQfm96LS';
    $room = 3091332;
//    $hipchat = new HipChat\HipChat($token);
		SSH::run(array(
      'cd /www/contentlaunch',
      'git pull origin master',
      'npm update',
      'php composer.phar update',
      'bower update',
      'php artisan migrate --env="test"',
      //'phpunit',
      'gulp',
  //    $hc->message_room($room, 'Test Deploy', 'Deployed changes to test server')
    ));
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
		);
	}

}
