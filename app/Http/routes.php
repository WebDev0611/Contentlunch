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

/**
 * Route Models
 */
Route::model('account', 'App\Account');
Route::model('buyingStage', 'App\BuyingStage');
Route::model('campaign', 'App\Campaign');
Route::model('connection', 'App\Connection');
Route::model('content', 'App\Content');
Route::model('idea', 'App\Idea');
Route::model('invite', 'App\AccountInvite');
Route::model('persona', 'App\Persona');
Route::model('task', 'App\Task');
Route::model('user', 'App\User');
Route::model('writerAccessPartialOrder', 'App\WriterAccessPartialOrder');

/* Login/Logout */
Route::auth();

/**
 * Onboarding - Account Invite redeeming
 */
Route::post('signup/invite', 'OnboardingController@createWithInvite');
Route::get('signup/invite/{invite}', [ 'as' => 'signupWithInvite', 'uses' => 'OnboardingController@signupWithInvite' ]);

Route::group([ 'middleware' => 'guest' ], function() {
    Route::get('signup', 'OnboardingController@signup');
    Route::post('signup', 'OnboardingController@process_signup');
    Route::post('signup/photo_upload', 'OnboardingController@signupPhotoUpload');
});

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', ['as' => 'dashboard', 'uses' => 'HomeController@index']);

    Route::get('invite', ['as' => 'inviteIndex', 'uses' => 'OnboardingInviteController@invite']);
    Route::post('invite/emails', ['as' => 'emailInvite', 'uses' => 'OnboardingInviteController@emailInvite']);
    Route::get('invite/{user}', ['as' => 'inviteUser', 'uses' => 'OnboardingInviteController@inviteUser']);

    Route::get('connect', ['as' => 'onboardingConnect', 'uses' => 'OnboardingController@connect']);

    Route::get('score', 'OnboardingController@score');

    Route::get('/home', 'HomeController@index');
    Route::get('/dashboard', 'AccountController@stats');

    Route::group(['prefix' => 'agencies'], function () {
        Route::get('/', ['as' => 'agencyIndex', 'uses' => 'AgencyController@index']);
        Route::post('/', ['as' => 'agencyStore', 'uses' => 'AgencyController@store']);
        Route::post('/select/{account}', ['as' => 'accountSelect', 'uses' => 'AccountController@selectAccount']);
    });

    Route::get('/analyze', 'AnalyzeController@index');

    Route::get('/plan', 'PlanController@index');
    Route::get('/plan/ideas', 'PlanController@ideas');
    Route::get('/plan/parked', 'PlanController@parked');

    Route::get('/plan/editor', 'PlanController@editor');
    Route::get('/plan/trends', 'PlanController@trends');
    Route::get('/plan/prescription', 'PlanController@prescription');

    Route::resource('/trending', 'TrendsController@trending');
    Route::resource('/influencers', 'InfluencersController@search');
    Route::resource('/topics', 'TopicsController@index');

    Route::get('/idea/{idea}', [ 'as' => 'ideaEditor', 'uses' => 'PlanController@editor' ]);
    Route::post('/idea/park','IdeaController@park');
    Route::post('/idea/update/{idea}','IdeaController@update');
    Route::post('/idea/reject/{id}','IdeaController@reject');
    Route::post('/idea/activate','IdeaController@activate');
    Route::get('/idea/write/{idea}', [ 'as' => 'ideaWrite', 'uses' => 'IdeaController@write' ]);

    Route::resource('/ideas', 'IdeaController', ['only' => [
        'index', 'show', 'store', 'park', 'activate'
    ]]);

    Route::get('/calendar', 'CalendarController@index');
    Route::get('/calendar/{year}/{month}', 'CalendarController@index');

    Route::get('/campaign','CampaignController@create');
    Route::post('/campaign', 'CampaignController@store');
    Route::get('/campaign/{campaign}', 'CampaignController@edit');
    Route::post('/campaign/{campaign}','CampaignController@update');

    Route::get('/daily', 'CalendarController@daily');
    Route::get('/daily/{year}/{month}/{day}', 'CalendarController@daily');

    Route::get('/weekly', 'CalendarController@weekly');
    Route::get('/weekly/{year}/{month}/{day}', 'CalendarController@weekly');

    Route::get('/campaigns', 'CalendarController@campaigns');

    Route::resource('/task/add', 'TaskController@store');
    Route::post('task/attachments', 'TaskAttachmentsController@store');
    Route::get('task/show/{id}', ['as' => 'taskShow', 'uses' => 'TaskController@show']);
    Route::post('task/update/{id}', 'TaskController@update');
    Route::post('task/close/{task}', 'TaskController@close');
    Route::delete('task/{task}', 'TaskController@destroy');

    Route::get('/content', ['as' => 'contentIndex', 'uses' => 'ContentController@index']);
    Route::get('/content/delete/{content_id}', ['as' => 'contentDelete', 'uses' => 'ContentController@delete']);
    Route::get('/content/publish/{content}', ['as' => 'contentPublish', 'uses' => 'ContentController@publishAndRedirect']);
    Route::get('/content/multipublish/{content}', ['as' => 'contentMultiPublish', 'uses' => 'ContentController@directPublish']);

    Route::get('/content/my', 'ContentController@my');

    // Facebook Callbacks
    //
    Route::get('callback/facebook', ['as' => 'facebookProvider', 'uses' => 'Connections\FacebookController@callback']);
    Route::post('callback/facebook/account/save', 'Connections\FacebookController@saveAccount');
    // -----------

    // Twitter Callbacks
    //
    Route::get('twitter/login', ['as' => 'twitterLogin', 'uses' => 'Connections\TwitterController@login']);
    Route::get('callback/twitter', ['as' => 'twitterCallback', 'uses' => 'Connections\TwitterController@callback']);
    Route::get('twitter/error', ['as' => 'twitterError', 'uses' => 'Connections\TwitterController@error']);

    // - Authorize
    Route::get('authorize/{provider}', ['as' => 'connectionProvider', 'uses' => 'ConnectionController@redirectToProvider']);
    Route::get('login/{provider}', ['as' => 'connectionCallback', 'uses' => 'ConnectionController@login']);

    // Wordpress Callback
    Route::get('callback/wordpress', ['as' => 'wordpressCallback', 'uses' => 'Connections\WordpressController@callback']);

    // HubSpot Callback
    Route::get('callback/hubspot', ['as' => 'hubspotCallback', 'uses' => 'Connections\HubspotController@callback']);

    // - Landing page for creating content
    Route::get('/create', 'ContentController@create');
    Route::post('/create/new', 'ContentController@store');

    // - create form page
    Route::get('/edit', ['as' => 'editIndex', 'uses' => 'ContentController@createContent']);
    Route::post('/edit', 'ContentController@editStore');

    Route::post('/edit/images', ['as' => 'imageContent', 'uses' => 'ContentController@images']);
    Route::post('/edit/attachments', ['as' => 'attachmentContent', 'uses' => 'ContentController@attachments']);

    // - editing content form page
    Route::get('/edit/{content}', ['as' => 'editContent', 'uses' => 'ContentController@editContent']);
    Route::post('/edit/{content}', 'ContentController@editStore');

    Route::get('/collaborate', 'CollaborateController@index');
    Route::get('/collaborate/linkedin', 'CollaborateController@linkedin');
    Route::get('/collaborate/twitter', 'CollaborateController@twitter');
    Route::get('/collaborate/bookmarks', 'CollaborateController@bookmarks');

    Route::get('/onboarding', 'OnboardingController@index');

    Route::group(['prefix' => 'settings'], function () {
        Route::get('/', ['as' => 'settingsIndex', 'uses' => 'SettingsController@index']);
        Route::post('/', ['as' => 'settingsUpdate', 'uses' => 'SettingsController@update']);
        Route::get('content', ['as' => 'settingsContentIndex', 'uses' => 'SettingsController@content']);
        Route::get('buying', ['as' => 'settingsBuyingIndex', 'uses' => 'SettingsController@content']);

        // Connection Routes
        Route::get('connections', ['as' => 'connectionIndex', 'uses' => 'SettingsController@connections']);
        Route::post('connections/create', ['as' => 'createConnection', 'uses' => 'ConnectionController@store']);
        Route::delete('connections/{connection}', ['as' => 'deactivateConnection', 'uses' => 'ConnectionController@delete']);

        Route::get('seo', ['as' => 'seoIndex', 'uses' => 'SettingsController@seo']);

        Route::post('personas', 'Settings\PersonasController@create');
        Route::get('personas', 'Settings\PersonasController@index');
        Route::delete('personas/{persona}', 'Settings\PersonasController@delete');

        Route::post('buying_stages', 'Settings\BuyingStagesController@create');
        Route::get('buying_stages', 'Settings\BuyingStagesController@index');
        Route::delete('buying_stages/{buyingStage}', 'Settings\BuyingStagesController@delete');

        Route::get('account', [ 'as' => 'settingsAccount', 'uses' => 'AccountSettingsController@index' ]);
    });

    Route::group(['prefix' => 'writeraccess'], function () {
        Route::get('categories', 'WriterAccessController@categories');
        Route::get('account', 'WriterAccessController@account');
        Route::get('assetTypes', 'WriterAccessController@assetTypes');
        Route::post('orders/create', 'WriterAccessController@createOrder');
        Route::get('orders', 'WriterAccessController@orders');
        Route::get('orders/{id}', 'WriterAccessController@orders');
        Route::get('projects', 'WriterAccessController@projects');
        Route::get('projects/{id}', 'WriterAccessController@projects');
        Route::get('projects/create/{name}', 'WriterAccessController@createProject');
        Route::get('expertises', 'WriterAccessController@expertises');
        Route::get('fees', 'WriterAccessPriceController@index');
        Route::get('fee', 'WriterAccessPriceController@fee');

        Route::post('partials', 'WriterAccessPartialOrderController@store');
        Route::post('partials/{id}', 'WriterAccessPartialOrderController@update');

        Route::post('orders/{writerAccessPartialOrder}/submit', [
            'as' => 'orderSubmit',
            'uses' => 'WriterAccessController@orderSubmit'
        ]);

        /**
         * Writer Access form pages.
         */
        Route::get('partials/order_setup/{writerAccessPartialOrder}', [
            'as' => 'orderSetup',
            'uses' => 'WriterAccessPartialOrderController@orderSetup'
        ]);

        Route::post('partials/upload/{writerAccessPartialOrder}', 'WriterAccessUploadController@store');

        Route::get('partials/order_audience/{writerAccessPartialOrder}', [
            'as' => 'orderAudience',
            'uses' => 'WriterAccessPartialOrderController@orderAudience'
        ]);

        Route::get('partials/order_review/{writerAccessPartialOrder}', [
            'as' => 'orderReview',
            'uses' => 'WriterAccessPartialOrderController@orderReview'
        ]);
    });

    Route::resource('writerAccessPrices', 'WriterAccessPriceController');
    Route::resource('writerAccessAssetTypes', 'WriterAccessAssetTypeController');


    Route::group(['prefix' => 'twitter'], function () {
        Route::get('followers', ['uses' => 'Connections\TwitterController@userSearch']);
    });

    /**
     * AJAX Helpers
     */
    Route::get('/api/connections', 'ConnectionController@index');
    Route::get('/api/campaigns', 'CampaignController@index');
    Route::get('/api/campaigns/collaborators', 'CampaignCollaboratorsController@accountCollaborators');
    Route::get('/api/campaigns/{campaign}/collaborators', 'CampaignCollaboratorsController@index');
    Route::get('/api/campaigns/{campaign}/tasks', 'CampaignTasksController@index');
    Route::get('/api/contents/{content}/collaborators', 'ContentCollaboratorsController@index');
    Route::post('/api/contents/{content}/collaborators', 'ContentCollaboratorsController@update');
    Route::get('/api/contents/{content}/tasks', 'ContentTasksController@index');
    Route::get('/api/ideas/{idea}/collaborators', 'IdeaCollaboratorsController@index');
    Route::post('/api/ideas/{idea}/collaborators', 'IdeaCollaboratorsController@update');
    Route::get('/api/account/members', 'AccountCollaboratorsController@index');
    Route::delete('/api/account/members/{id}', 'AccountCollaboratorsController@delete');
    Route::get('/api/tasks', 'TaskController@index');
    Route::post('/api/trends/share/{connection}', [ 'as' => 'trendShare', 'uses' => 'ContentController@trendShare' ]);
    Route::post('/search', [ 'as' => 'searchIndex', 'uses' => 'SearchController@index' ]);
});
