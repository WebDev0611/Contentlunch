<?php

/**
 * API calls, prefixed with /api
 * Should return json responses
 * @todo : Add authentication filter
 * @todo : Determine consistent response structure
 */
Route::group(['prefix' => 'api'], function()
{

  Route::post('account/request_update', 'AccountController@request_update_email');

  Route::resource('account', 'AccountController', [
    'only' => ['index', 'store', 'show', 'update', 'destroy']
  ]);

  Route::post('account/{id}/add_user', 'AccountUserController@store');
  Route::get('account/{id}/users', 'AccountUserController@show');

  Route::get('account/{accountID}/campaigns/export-csv', 'CampaignController@download_csv');
  Route::resource('account/{id}/campaigns', 'CampaignController', [
    'only' => ['index', 'store', 'show', 'update', 'destroy']
  ]);

  Route::resource('account/{accountID}/campaigns/{campaignID}/tasks', 'CampaignTaskController', [
    'only' => ['index', 'store', 'show', 'update', 'destroy']
  ]);

  Route::resource('account/{accountID}/campaigns/{campaignID}/uploads', 'CampaignUploadController', [
    'only' => ['index', 'store', 'destroy']
  ]);


  Route::get('account/{accountID}/content-tasks', 'ContentTaskGroupController@getAllTasks');


  Route::get('account/{accountID}/content/export-csv', 'ContentController@download_csv');
  // Launch a content connection
  Route::post('account/{accountID}/content/{contentID}/launch/{accountConnectionID}', 'ContentController@launch');
  Route::resource('account/{id}/content', 'ContentController', [
    'only' => ['index', 'store', 'show', 'update', 'destroy']
  ]);

  Route::get('account/{id}/content/{contentid}/activity', 'ContentController@showActivities');

  // when it comes to collaborators, they can be attached to both campaigns and content
  // collabType can be content or campaigns only (enforced in the controller)
  Route::resource('account/{accountid}/{collabType}/{id}/collaborators', 'CollaboratorsController', [
    'only' => ['index', 'store', 'destroy']
  ]);

  // when it comes to guest collaborators, they can be attached to both campaigns and content
  Route::resource('account/{accountID}/{contentType}/{contentID}/guest-collaborators', 'GuestCollaboratorsController', [
    'only' => ['index', 'destroy']
  ]);
  Route::get('guest-collaborators/me', 'GuestCollaboratorsController@me');

  // we need a route to get a single Guest Collaborator without being logged in (i.e. no account/content/campaign ID)
  Route::get('guest-collaborators/{accessCode}', 'GuestCollaboratorsController@show');
  Route::get('guest-collaborators/{guestID}/link-account', 'GuestCollaboratorsController@linkAccount');

  Route::get('guest-collaborators/concepts/{connectionUserID}', 'GuestCollaboratorsController@concepts');

  Route::resource('account/{accountID}/content/{contentID}/comments', 'ContentCommentsController', [
    'only' => ['index', 'store']
  ]);

  Route::get('add-connection', 'AccountConnectionsController@addConnection');
  Route::resource('account/{id}/connections', 'AccountConnectionsController', [
    'only' => ['index', 'create', 'show', 'update', 'destroy']
  ]);
  //Route::get('account/{accountID}/add-connection/{connectionID}', 'AccountConnectionsController@addConnection');
  Route::any('account/{id}/connections/{connectionId}/{action}', 'AccountConnectionsController@actionRouter');

  Route::resource('account/{id}/conferences', 'ConferencesController', [
    'only' => ['index', 'store', 'show', 'update', 'destroy']
  ]);

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

  Route::resource('account/{id}/uploads', 'UploadController', [
    'only' => ['index', 'store', 'show', 'update', 'destroy']
  ]);

  Route::group(['prefix' => 'auth'], function() {
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

  Route::resource('connections', 'ConnectionController', [
    'only' => ['index']
  ]);

  Route::resource('content-types', 'ContentTypeController', [
    'only' => ['index']
  ]);

  Route::resource('library', 'LibraryController', [
    'only' => ['index', 'store', 'show', 'update', 'destroy', 'storeUpload']
  ]);

  Route::resource('library/{id}/uploads', 'LibraryUploadsController', [
    'only' => ['index', 'store', 'update', 'destroy']
  ]);

  Route::resource('campaign-types', 'CampaignTypeController', [
    'only' => ['index']
  ]);

  Route::resource('modules', 'ModuleController', [
    'only' => ['index']
  ]);

  Route::resource('permission', 'PermissionController', [
    'only' => ['index']
  ]);

  // No store or destroy route, these roles should already be seeded and nondeletable
  Route::resource('role', 'RoleController', [
    'only' => ['index', 'show', 'update']
  ]);

  Route::resource('subscription', 'SubscriptionController', [
    'only' => ['index', 'show', 'update']
  ]);

  Route::get('uploads/{id}/download', 'UploadController@download');
  Route::post('uploads/{id}/rating', 'UploadController@rating');

  Route::resource('user', 'UserController', [
    'only' => ['index', 'store', 'show', 'update', 'destroy']
  ]);
  Route::post('/user/{id}/image', 'UserController@postProfileImage');
  Route::post('/user/{id}/preferences/{key}', 'UserController@savePreferences');

  Route::get('impersonate/{id}', 'AdminController@impersonate');

  Route::resource('account/{accountID}/content/{contentID}/task-group', 'ContentTaskGroupController', [
    'only' => ['index', 'update']
  ]);

  Route::resource('forum-thread', 'ForumThreadController', [
    'only' => ['index', 'store', 'show', 'update', 'destroy']
  ]);

  Route::resource('forum-thread/{threadID}/reply', 'ForumThreadReplyController', [
    'only' => ['index', 'store', 'update', 'destroy']
  ]);

  Route::resource('account/{accountID}/{conceptType}/{conceptID}/brainstorm', 'BrainstormController', [
    'only' => ['index', 'store', 'show', 'update', 'destroy']
  ]);

  Route::get('account/{accountID}/brainstorm', 'BrainstormController@all');

  Route::get('test', 'ConferencesController@test');

});

Route::get('password/reset/{code}', 'AuthController@check_reset');


/**
 * Catchall route.
 * Any routes that aren't already matched by laravel should
 * be passed on to angular's routing.
 */
Route::any('{all}', function()
{
  // If route starts with api and the route wasn't matched, return an error response
  if (Request::is('api/*')) {
    return Response::json([
      'error' => 'Unknown route: '. Request::path()
    ], 400);
  }
  return View::make('master');
})->where('all', '.*');
