<?php

// Confide routes

Route::get( 'user/confirm/{code}',         'AuthController@confirm');
//Route::get( 'user/reset/{token}', 'AuthController@reset_password');


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

	Route::get('account/{id}/subscription', 'SubscriptionController@get_subscription');
	Route::post('account/{id}/subscription', 'SubscriptionController@post_subscription');

	Route::group(array('prefix' => 'auth'), function() {
		// Attempt to login a user
		Route::post('/', 'AuthController@do_login');
		// Gets the currently logged in user, or guest
		Route::get('/', 'AuthController@show_current');
		// Logout
		Route::get('/logout', 'AuthController@logout');
		// Forgot password, sends reset email
		Route::post('/forgot_password', 'AuthController@do_forgot_password');
		// Resets user's password, requires a token from forgot_password
		Route::post('/reset_password', 'AuthController@do_reset_password');
		// Confirm user's account with confirmation code
		Route::post('/confirm', 'AuthController@do_confirm');
	});

	Route::resource('role', 'RoleController', array(
		'only' => array('index', 'store', 'show', 'update', 'destroy')
	));

	Route::resource('user', 'UserController', array(
		'only' => array('index', 'store', 'show', 'update', 'destroy')
	));
	Route::post('/user/{id}/image', 'UserController@postProfileImage');

	Route::get('test', function()
	{
		return array(
			array('id' => 1, 'title' => 'test 1'),
			array('id' => 2, 'title' => 'test 2'),
			array('id' => 3, 'title' => 'test 3')
		);
	});

});


/**
 * Catchall route.
 * Any routes that aren't already matched by laravel should
 * be passed on to angular's routing.
 */
Route::any('{all}', function()
{
	return View::make('master');
})->where('all', '.*');
