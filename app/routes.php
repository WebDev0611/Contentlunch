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
Route::get( 'user/create',                 'UserController@create');
Route::post('user',                        'UserController@store');
Route::get( 'user/login',                  'UserController@login');
Route::post('user/login',                  'UserController@do_login');
Route::get( 'user/confirm/{code}',         'UserController@confirm');
Route::get( 'user/forgot_password',        'UserController@forgot_password');
Route::post('user/forgot_password',        'UserController@do_forgot_password');
Route::get( 'user/reset_password/{token}', 'UserController@reset_password');
Route::post('user/reset_password',         'UserController@do_reset_password');
Route::get( 'user/logout',                 'UserController@logout');

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
	
	Route::get('test', function()
	{
		return array(
			array('id' => 1, 'title' => 'test 1'),
			array('id' => 2, 'title' => 'test 2'),
			array('id' => 3, 'title' => 'test 3')
		);
	});

});