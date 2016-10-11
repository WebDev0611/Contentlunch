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
Route::get('score', 'OnboardingController@score');
Route::get('connect', 'OnboardingController@connect');
Route::post('signup', 'OnboardingController@process_signup');


Route::get('invite', ['as' => 'inviteIndex', 'uses' =>'OnboardingInviteController@invite'] );
Route::group(['prefix' => 'invite', /*'middleware' => ['auth']*/], function() {
	Route::post('emails', ['as' => 'emailInvite', 'uses' => 'OnboardingInviteController@emailInvite'] );
});



Route::get('/home','AccountController@index');
Route::get('/dashboard','AccountController@stats');

Route::get('/agency','AgencyController@index');

Route::get('/analyze','AnalyzeController@index');

Route::get('/plan','PlanController@index');
Route::get('/plan/ideas','PlanController@ideas');
Route::get('/plan/parked','PlanController@parked');

Route::get('/plan/editor','PlanController@editor');
Route::get('/plan/trends','PlanController@trends');
Route::get('/plan/prescription','PlanController@prescription');

Route::resource('/trending', 'TrendsController@trending');
Route::resource('/influencers', 'InfluencersController@search');
Route::resource('/topics', 'TopicsController@index');


Route::get('/idea/{id}','PlanController@editor');

Route::resource('/ideas', 'IdeaController', ['only' => [
    'index', 'show','store'
]]);

Route::get('/calendar','CalendarController@index');
Route::get('/calendar/{year}/{month}','CalendarController@index');

Route::get('/campaign','CampaignController@index');
Route::post('/campaign/create','CampaignController@create');

Route::get('/campaign/edit/{campaign}','CampaignController@edit');
Route::post('/campaign/edit/{campaign}','CampaignController@edit');

Route::get('/daily','CalendarController@daily');
Route::get('/daily/{year}/{month}/{day}','CalendarController@daily');

Route::get('/weekly','CalendarController@weekly');
Route::get('/weekly/{year}/{month}/{day}','CalendarController@weekly');

Route::get('/campaigns','CalendarController@campaigns');

Route::resource('/task/add','TaskController@store');


Route::get('/content',  ['as' => 'contentIndex', 'uses' =>'ContentController@index']);
Route::get('/content/delete/{content_id}', [ 'as' => 'contentDelete', 'uses' => 'ContentController@delete' ]);
Route::get('/content/publish/{content}', ['as' => 'contentPublish', 'uses' =>'ContentController@publishAndRedirect' ]);
Route::get('/content/multipublish/{content}', [ 'as' => 'contentMultiPublish', 'uses' => 'ContentController@directPublish' ]);

// Facebook Callbacks
//
Route::get('callback/facebook',  ['as' => 'facebookProvider', 'uses' =>'Connections\FacebookController@callback']);
Route::post('callback/facebook/account/save','Connections\FacebookController@saveAccount');
// -----------

// Twitter Callbacks
//
Route::get('twitter/login', [ 'as' => 'twitterLogin', 'uses' => 'Connections\TwitterController@login' ]);
Route::get('callback/twitter', [ 'as' => 'twitterCallback', 'uses' => 'Connections\TwitterController@callback' ]);
Route::get('twitter/error', [ 'as' => 'twitterError', 'uses' => 'Connections\TwitterController@error' ]);

// - Authorize
Route::get('authorize/{provider}',  ['as' => 'connectionProvider', 'uses' =>'ConnectionController@redirectToProvider']);
Route::get('login/{provider}',  ['as' => 'connectionCallback', 'uses' =>'ConnectionController@login']);

// Wordpress Callback
Route::get('callback/wordpress', [ 'as' => 'wordpressCallback', 'uses' => 'WordpressController@callback' ]);

// - Landing page for creating content
Route::get('/create','ContentController@create');
Route::post('/create/new','ContentController@store');

// - create form page
Route::get('/edit', ['as' => 'editIndex', 'uses' => 'ContentController@createContent']);
Route::post('/edit','ContentController@editStore');
// - editing content form page
Route::get('/edit/{content}', ['as' => 'editContent', 'uses' =>'ContentController@editContent']);
Route::post('/edit/{content}','ContentController@editStore');

Route::get('/get_written','ContentController@get_written');
Route::get('/get_written/{step}','ContentController@get_written');


Route::get('/collaborate','CollaborateController@index');
Route::get('/collaborate/linkedin','CollaborateController@linkedin');
Route::get('/collaborate/twitter','CollaborateController@twitter');
Route::get('/collaborate/bookmarks','CollaborateController@bookmarks');

Route::get('/onboarding','OnboardingController@index');

Route::group(['prefix' => 'settings'], function() {
    Route::get('/', [ 'as' => 'settingsIndex', 'uses' => 'SettingsController@index' ]);
    Route::post('/', [ 'as' => 'settingsUpdate', 'uses' => 'SettingsController@update']);
	Route::get('content',  ['as' => 'settingsContentIndex', 'uses' => 'SettingsController@content']);

    // Connection Routes
	Route::get('connections', ['as' => 'connectionIndex', 'uses' => 'SettingsController@connections']);
	Route::post('connections/create', ['as' => 'createConnection', 'uses' => 'SettingsController@connectionCreate'] );

	Route::get('seo', ['as' => 'seoIndex', 'uses' =>'SettingsController@seo']);
	Route::get('buying','SettingsController@buying');
});

Route::group(['prefix' => 'twitter'], function() {
    Route::get('followers', [ 'uses' => 'Connections\TwitterController@userSearch' ]);
});

/**
 * AJAX Helpers
 */
Route::group([ 'prefix' => 'api', 'middleware' => [ ] ], function() {
    Route::get('/connections', [ 'as' => 'connectionAjaxIndex', 'uses' => 'ConnectionController@index' ]);
});