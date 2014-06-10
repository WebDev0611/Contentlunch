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

  Route::resource('account/{id}/campaigns', 'CampaignController', [
    'only' => ['index', 'store', 'show', 'update', 'destroy']
  ]);

  Route::resource('account/{accountID}/campaigns/{campaignID}/tasks', 'CampaignTaskController', [
    'only' => ['index', 'store', 'show', 'update', 'destroy']
  ]);

  Route::get('account/{accountID}/content-tasks', 'ContentTaskGroupController@getAllTasks');


  Route::resource('account/{id}/content', 'ContentController', [
    'only' => ['index', 'store', 'show', 'update', 'destroy']
  ]);

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

  Route::resource('account/{accountID}/content/{contentID}/comments', 'AccountContentCommentsController', [
    'only' => ['index', 'store']
  ]);

  Route::get('add-connection', 'AccountConnectionsController@addConnection');
  Route::resource('account/{id}/connections', 'AccountConnectionsController', [
    'only' => ['index', 'create', 'show', 'update', 'destroy']
  ]);
  //Route::get('account/{accountID}/add-connection/{connectionID}', 'AccountConnectionsController@addConnection');
  Route::any('account/{id}/connections/{connectionId}/{action}', 'AccountConnectionsController@actionRouter');

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

  Route::resource('user', 'UserController', [
    'only' => ['index', 'store', 'show', 'update', 'destroy']
  ]);
  Route::post('/user/{id}/image', 'UserController@postProfileImage');

  Route::get('impersonate/{id}', 'AdminController@impersonate');

  Route::get   ('account/{accountId}/content/{contentId}/task-group',               'ContentTaskGroupController@index'  );
  Route::put   ('account/{accountId}/content/{contentId}/task-group/{taskGroupId}', 'ContentTaskGroupController@update' );
  // Route::post  ('account/{accountId}/content/{contentId}/task-group',               'ContentTaskGroupController@store'  );
  // Route::get   ('account/{accountId}/content/{contentId}/task-group/{taskGroupId}', 'ContentTaskGroupController@show'   );
  // Route::delete('account/{accountId}/content/{contentId}/task-group/{taskGroupId}', 'ContentTaskGroupController@destroy');
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
    return Response::json([
      'error' => 'Unknown route: '. Request::path()
    ], 400);
  }
  return View::make('master');
})->where('all', '.*');
