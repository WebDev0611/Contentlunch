<?php

/**
 * API calls, prefixed with /api
 * Should return json responses
 * @todo : Add authentication filter
 * @todo : Determine consistent response structure
 */
Route::group(array('prefix' => 'api'), function()
{

	Route::post('account/request_update', 'AccountController@request_update_email');

	Route::resource('account', 'AccountController', array(
		'only' => array('index', 'store', 'show', 'update', 'destroy')
	));

	Route::post('account/{id}/add_user', 'AccountUserController@store');
	Route::get('account/{id}/users', 'AccountUserController@show');

	Route::get('account/{id}/subscription', 'AccountSubscriptionController@get_subscription');
	Route::post('account/{id}/subscription', 'AccountSubscriptionController@post_subscription');

	Route::post('account/{id}/resend_creation_email', 'AccountController@resend_creation_email');

	Route::get('account/{id}/roles', 'AccountRoleController@index');
	Route::post('account/{id}/roles', 'AccountRoleController@store');
	Route::put('account/{id}/roles/{roleid}', 'AccountRoleController@update');
	Route::delete('account/{id}/roles/{roleid}', 'AccountRoleController@destroy');

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
		// Impersonate as a user
		Route::post('impersonate', 'AuthController@impersonate');
	});

	Route::resource('permission', 'PermissionController', array(
		'only' => array('index')
	));

	// No store or destroy route, these roles should already be seeded and nondeletable
	Route::resource('role', 'RoleController', array(
		'only' => array('index', 'show', 'update')
	));

	Route::resource('subscription', 'SubscriptionController', array(
		'only' => array('index', 'show', 'update')
	));

	Route::resource('user', 'UserController', array(
		'only' => array('index', 'store', 'show', 'update', 'destroy')
	));
	Route::post('/user/{id}/image', 'UserController@postProfileImage');

	Route::get('impersonate/{id}', 'AdminController@impersonate');

	Route::get('test', function()
	{
		return array(
			array('id' => 1, 'title' => 'test 1'),
			array('id' => 2, 'title' => 'test 2'),
			array('id' => 3, 'title' => 'test 3')
		);
	});

});

Route::get('deploy/{environment}/{token}', function ($environment, $token) {

});


/**
 * Catchall route.
 * Any routes that aren't already matched by laravel should
 * be passed on to angular's routing.
 */
Route::any('{all}', function()
{
	// If route starts with api and the route wasn't matched, return an error response
  if (Request::is('api/*')) {
    return Response::json(array(
      'error' => 'Unknown route: '. Request::path()
    ), 400);
  }
	return View::make('master');
})->where('all', '.*');
