<?php
/**
 * If nothing else matches, pass this route along to let
 * angular handle the route.
 * Return the master layout view
 * @todo : Add an authentication filter?
 */
Route::get('{any}', function()
{
	return View::make('master');
});

// Homepage route
// @todo : Not sure what to do with this yet
Route::get('/', function()
{
	return View::make('master');
});

// Confide routes

Route::get( 'user/confirm/{code}',         'AuthController@confirm');
Route::get( 'user/forgot_password',        'AuthController@forgot_password');
Route::post('user/forgot_password',        'AuthController@do_forgot_password');
Route::get( 'user/reset_password/{token}', 'AuthController@reset_password');
Route::post('user/reset_password',         'AuthController@do_reset_password');


/**
 * API calls, prefixed with /api
 * Should return json responses
 * @todo : Add authentication filter
 * @todo : Determine consistent response structure
 */
Route::group(array('prefix' => 'api'), function()
{

	Route::resource('account', 'AccountController', array(
		'only' => array('index', 'store', 'show', 'update', 'destroy')
	));

	Route::post('account/{id}/add_user', 'AccountController@add_user');

	Route::get('account/{id}/users', 'AccountController@get_users');

	Route::group(array('prefix' => 'auth'), function() {
		// Attempt to login a user
		Route::post('/', 'AuthController@do_login');
		// Gets the currently logged in user, or guest
		Route::get('/', 'AuthController@show_current');
		// Logout
		Route::get('/logout', 'AuthController@logout');
	});

	Route::resource('role', 'RoleController', array(
		'only' => array('index', 'store', 'show', 'update', 'destroy')
	));

	Route::resource('user', 'UserController', array(
		'only' => array('index', 'store', 'show', 'update', 'destroy')
	));
	
	Route::get('test', function()
	{
		return array(
			array('id' => 1, 'title' => 'test 1'),
			array('id' => 2, 'title' => 'test 2'),
			array('id' => 3, 'title' => 'test 3')
		);
	});

});
