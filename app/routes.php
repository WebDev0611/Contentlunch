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

	Route::get('account/{id}/connections', 'AccountConnectionsController@get_connections');
	Route::post('account/{id}/connections', 'AccountConnectionsController@post_connection');
	Route::put('account/{id}/connections/{connection_id}', 'AccountConnectionsController@put_connection');
	Route::delete('account/{id}/connections/{connection_id}', 'AccountConnectionsController@delete_connection');

	Route::get('account/{id}/content-settings', 'AccountContentSettingsController@get_settings');
	Route::put('account/{id}/content-settings', 'AccountContentSettingsController@save_settings');

	Route::get('account/{id}/subscription', 'AccountSubscriptionController@get_subscription');
	Route::post('account/{id}/subscription', 'AccountSubscriptionController@post_subscription');

	Route::post('account/{id}/resend_creation_email', 'AccountController@resend_creation_email');

	Route::get('account/{id}/roles', 'AccountRoleController@index');
	Route::post('account/{id}/roles', 'AccountRoleController@store');
	Route::get('account/{id}/roles/{roleid}', 'AccountRoleController@showRole');
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

	Route::resource('content', 'ContentController', array(
		'only' => array('index', 'store', 'show', 'update', 'destroy')
	));

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
