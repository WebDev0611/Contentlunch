<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Default Remote Connection Name
	|--------------------------------------------------------------------------
	|
	| Here you may specify the default connection that will be used for SSH
	| operations. This name should correspond to a connection name below
	| in the server list. Each connection will be manually accessible.
	|
	*/

	'default' => 'test',

	/*
	|--------------------------------------------------------------------------
	| Remote Server Connections
	|--------------------------------------------------------------------------
	|
	| These are the servers that will be accessible via the SSH task runner
	| facilities of Laravel. This feature radically simplifies executing
	| tasks on your servers, such as deploying out these applications.
	|
	*/

	'connections' => array(

    'test' => array(
      'host'      => '10.254.2.21',
      'username'  => 'webdeploy',
      'password'  => 'pQtALjnc6gPKL6mqDvAe',
      'key'       => '',
      'keyphrase' => '',
      'root'      => '/www/contentlaunch/',
    ),


		'production' => array(
			'host'      => '',
			'username'  => '',
			'password'  => '',
			'key'       => '',
			'keyphrase' => '',
			'root'      => '/var/www',
		),

	),

	/*
	|--------------------------------------------------------------------------
	| Remote Server Groups
	|--------------------------------------------------------------------------
	|
	| Here you may list connections under a single group name, which allows
	| you to easily access all of the servers at once using a short name
	| that is extremely easy to remember, such as "web" or "database".
	|
	*/

	'groups' => array(

		'web' => array('production')

	),

);
