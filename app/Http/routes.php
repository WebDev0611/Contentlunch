<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

/* Login/Logout */
/*Route::get('login', 'AuthController@login');
Route::post('login', 'AuthController@login');
Route::get('logout', 'AuthController@logout');
*/
Route::auth();

Route::get('/', 'HomeController@index');


/* OnBoarding */
Route::get('signup', 'OnboardingController@signup');
Route::get('invite', 'OnboardingController@invite');
Route::get('score', 'OnboardingController@score');
Route::get('connect', 'OnboardingController@connect');
Route::post('signup', 'OnboardingController@process_signup');


Route::get('/home','AccountController@index');
Route::get('/home/tasks','AccountController@tasks');

Route::get('/analyze','AnalyzeController@index');

Route::get('/plan','PlanController@index');
Route::get('/plan/ideas','PlanController@ideas');
Route::get('/plan/parked','PlanController@parked');

Route::get('/plan/editor','PlanController@editor');
Route::get('/plan/trends','PlanController@trends');
Route::get('/plan/prescription','PlanController@prescription');

Route::get('/calendar','CalendarController@index');
Route::get('/calendar/{year}/{month}','CalendarController@index');

Route::get('/daily','CalendarController@daily');
Route::get('/daily/{year}/{month}/{day}','CalendarController@daily');

Route::get('/weekly','CalendarController@weekly');
Route::get('/weekly/{year}/{month}/{day}','CalendarController@weekly');

Route::get('/campaigns','CalendarController@campaigns');

Route::get('/content','ContentController@index');
Route::get('/collaborate','CollaborateController@index');
Route::get('/onboarding','OnboardingController@index');

Route::get('/settings','SettingsController@index');

Route::group(['prefix' => 'settings'], function() {
	Route::get('content','SettingsController@content');
	// - Connection Routes
	Route::get('connections', ['as' => 'connectionIndex', 'uses' => 'SettingsController@connections']);
	Route::post('connections/create', ['as' => 'createConnection', 'uses' => 'SettingsController@connectionCreate'] );


	Route::get('seo','SettingsController@seo');
	Route::get('buying','SettingsController@buying');
});


