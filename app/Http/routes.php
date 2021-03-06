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
    Route::get('_qebot', function(Request $request){
         return App::call('App\Tasks\QebotBilling@init');
    })->name('_qebot');

Route::group(['middleware' => ['fw-block-bl' ]], function () {

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

    // Authentication Routes...
    Route::get('login', 'Auth\AuthController@showLoginForm')->name('login');
    Route::post('login', 'Auth\AuthController@login');
    Route::get('logout', 'Auth\AuthController@logout')->name('logout');

    // Registration Routes...
    Route::get('register', 'Auth\AuthController@showRegistrationForm')->name('register');
    Route::post('register', 'Auth\AuthController@register');

    // Password Reset Routes...
    Route::get('password/reset/{token?}', 'Auth\PasswordController@showResetForm');
    Route::post('password/email', 'Auth\PasswordController@sendResetLinkEmail');
    Route::post('password/reset', 'Auth\PasswordController@reset');

    /**
     * Onboarding - Account Invite redeeming
     */
    Route::post('signup/invite', 'OnboardingController@createWithInvite');
    Route::get('signup/invite/{invite}', 'OnboardingController@signupWithInvite')->name('signupWithInvite');
    Route::post('signup/photo_upload', 'OnboardingController@signupPhotoUpload');

    Route::group(['middleware' => 'guest'], function () {
        Route::get('signup', 'OnboardingController@signup2');
        Route::get('signup2', 'OnboardingController@signup2');
        Route::post('signup', 'OnboardingController@process_signup');
        Route::get('/api/qebot/users/create', function(){
            return response()
                ->json(['error' => 'HTTP Method Not Allowed', 'messages' => ["Please use POST to use this endpoint."]]);
        });
        Route::get('/api/qebot/users/login', function(){
            return response()
                ->json(['error' => 'HTTP Method Not Allowed', 'messages' => ["Please use POST to use this endpoint."]]);
        });
        Route::get('/api/qebot/users/logout', function(){
            return response()
                ->json(['error' => 'HTTP Method Not Allowed', 'messages' => ["Please use POST to use this endpoint."]]);
        });
        Route::get('/api/qebot/users/activate', function(){
            return response()
                ->json(['error' => 'HTTP Method Not Allowed', 'messages' => ["Please use POST to use this endpoint."]]);
        });
        Route::get('/api/qebot/users/deactivate', function(){
            return response()
                ->json(['error' => 'HTTP Method Not Allowed', 'messages' => ["Please use POST to use this endpoint."]]);
        });
        Route::post('/api/qebot/users/create', "QebotLoginController@create");
        Route::post('/api/qebot/users/login', "QebotLoginController@login");
        Route::post('/api/qebot/users/logout', "QebotLoginController@logout");
        Route::post('/api/qebot/users/activate', "QebotLoginController@activate");
        Route::post('/api/qebot/users/deactivate', "QebotLoginController@deactivate");
    });

    Route::group([ 'prefix' => 'guests' ], function() {
        Route::get('signup/{guestInvite}', 'GuestController@create')->name('guests.create');
        Route::post('signup/{guestInvite}', 'GuestController@store')->name('guests.store');
    });

    Route::group(['middleware' => ['auth', 'subscription']], function () {
        Route::get('/', 'HomeController@index')->name('dashboard');

        Route::get('invite', 'OnboardingInviteController@invite')->name('inviteIndex');
        Route::post('invite/emails', 'OnboardingInviteController@emailInvite')->name('emailInvite');
        Route::get('invite/{user}', 'OnboardingInviteController@inviteUser')->name('inviteUser');

        Route::get('connect', 'OnboardingController@connect')->name('onboardingConnect');

        Route::get('score', 'OnboardingController@score')->name("onboarding.score");

        Route::get('/home', 'HomeController@index');
        Route::get('/dashboard', 'AccountController@stats');

        Route::group(['prefix' => 'agencies', 'middleware' => 'agency'], function () {
            Route::get('/', 'AgencyController@index')->name('agencyIndex');
            Route::post('/', 'AgencyController@store')->name('agencyStore');
            Route::post('/select/{account}', 'AccountController@selectAccount')->name('accountSelect');
        });

        Route::get('/analyze', 'AnalyzeController@index');

        Route::get('/plan', 'PlanController@index');
        Route::get('/plan/ideas', 'PlanController@ideas');
        Route::get('/plan/parked', 'PlanController@parked');

        Route::get('/plan/editor', 'PlanController@editor');
        Route::get('/plan/trends', 'PlanController@trends');
        Route::get('/plan/prescription', 'PlanController@prescription')->name('prescription');
        Route::post('/plan/prescription', 'ContentPrescriptionController@showPrescription')->name('getPrescription');

        Route::resource('/trending', 'TrendsController@trending');
        Route::resource('/topics', 'TopicsController@index');

        Route::group(['prefix' => 'influencers'], function() {
            Route::get('/', 'InfluencersController@search')->name('influencers.search');

            Route::get('/bookmarks', 'InfluencersController@index')->name('influencers.bookmarks');
            Route::post('/bookmarks', 'InfluencersController@toggleBookmark')->name('influencers.toggle_bookmark');
        });

        Route::get('/collaborate', 'CollaborateController@index')->name('collaborate.index');

        Route::get('/idea/{idea}', 'IdeaController@edit')->name('ideas.edit')->middleware('can:show,idea');
        Route::post('/idea/{idea}/activate', 'IdeaController@activate')->name('ideas.activate')->middleware('can:update,idea');
        Route::post('/idea/{idea}/park', 'IdeaController@park')->name('ideas.park')->middleware('can:update,idea');
        Route::post('/idea/{idea}/update', 'IdeaController@update')->name('ideas.update')->middleware('can:update,idea');
        Route::post('/idea/{idea}/reject', 'IdeaController@reject')->name('ideas.reject')->middleware('can:update,idea');
        Route::get('/idea/{idea}/write', 'IdeaController@write')->name('ideas.write')->middleware('can:write,idea');

        Route::post('/ideas', 'IdeaController@store')->name('ideas.store');
        Route::get('/ideas', 'IdeaController@index')->name('ideas.index');

        Route::group(['middleware' => ['auth', 'calendar']], function () {
            Route::get('/calendar/{id?}', 'CalendarController@index')->name('calendarMonthly');
            Route::get('/calendar/{id?}/{year}/{month}', 'CalendarController@index');
            Route::get('/daily/{id?}', 'CalendarController@daily')->name('calendarDaily');
            Route::get('/daily/{id?}/{year}/{month}/{day}', 'CalendarController@daily');
            Route::get('/weekly/{id?}', 'CalendarController@weekly')->name('calendarWeekly');
            Route::get('/weekly/{id?}/{year}/{month}/{day}', 'CalendarController@weekly');
            Route::get('/calendar/{id}/contents', 'CalendarController@getContents');
            Route::get('/calendar/{id}/tasks', 'CalendarController@getTasks');
            Route::get('/calendar/{id}/ideas', 'CalendarController@getIdeas');
        });
        Route::get('/calendar/my', 'CalendarController@my');
        Route::post('/calendar/add', 'CalendarController@create');

        Route::get('/campaign', 'CampaignController@create')->name('campaigns.create');
        Route::post('/campaign', 'CampaignController@store')->name('campaigns.store');
        Route::get('/campaign/{campaign}', 'CampaignController@edit')->name('campaigns.edit');
        Route::post('/campaign/{campaign}', 'CampaignController@update')->name('campaigns.update');
        Route::get('/campaign/{campaign}/park', 'CampaignController@park')->name('campaigns.park');
        Route::get('/campaign/{campaign}/deactivate', 'CampaignController@deactivate')->name('campaigns.deactivate');
        Route::get('/campaign/{campaign}/activate', 'CampaignController@activate')->name('campaigns.activate');
        Route::get('/campaign/{campaign}/delete', 'CampaignController@destroy')->name('campaigns.destroy');

        Route::post('/campaign/attachments', 'CampaignAttachmentController@store')->name('campaign_attachments.store');

        Route::get('/campaigns', 'CalendarController@campaigns');

        Route::post('/task/add', 'TaskController@store')->middleware('format_datetime:due_date,m/d/Y H:i')
                                                        ->middleware('format_datetime:start_date,m/d/Y H:i');
        Route::post('task/attachments', 'TaskAttachmentsController@store');
        Route::get('task/show/{task}', 'TaskController@edit')->name('tasks.edit');
        Route::post('task/update/{task}', 'TaskController@update');
        Route::post('task/close/{task}', 'TaskController@close');
        Route::post('task/open/{task}', 'TaskController@open');
        Route::delete('task/{task}', 'TaskController@destroy');

        Route::get('/content', 'ContentController@index')->name('contents.index');
        Route::get('/content/delete/{content_id}', 'ContentController@delete')->name('contentDelete');
        Route::get('/content/publish/{content}', 'ContentController@publishAndRedirect')->name('contentPublish');
        Route::get('/content/multipublish/{content}', 'ContentController@directPublish')->name('contentMultiPublish');

        Route::get('/content/archived', 'ArchivedContentController@index')->name('archived_contents.index');
        Route::get('/content/{content}/archive', 'ArchivedContentController@update')->name('archived_contents.update');

        Route::get('/content/orders', 'ContentController@orders')->name('content_orders.index');
        Route::get('/content/orders/{id}', 'WriterAccessOrdersController@show')->name('contentOrder');
        Route::get('/content/orders/approve/{id}', 'WriterAccessController@orderApprove')->name('orderApprove');
        Route::get('/content/orders/delete/{id}', 'ContentController@orderDelete')->name('orderDelete');
        Route::get('/content/my', 'ContentController@my');

        Route::get('/content/campaigns', 'CampaignController@dashboardIndex')->name('campaigns.index');

        // Facebook Callbacks
        //
        Route::get('callback/facebook', 'Connections\FacebookController@callback');
        Route::post('callback/facebook/account/save', 'Connections\FacebookController@saveAccount');
        // -----------

        // Twitter Callbacks
        //
        Route::get('twitter/login', 'Connections\TwitterController@login')->name('twitterLogin');
        Route::get('callback/twitter', 'Connections\TwitterController@callback')->name('twitterCallback');
        Route::get('twitter/error', 'Connections\TwitterController@error')->name('twitterError');

        // - Authorize
        Route::get('authorize/{provider}', 'ConnectionController@redirectToProvider')->name('connectionProvider');
        Route::get('login/{provider}', 'ConnectionController@login')->name('connectionCallback');

        // Wordpress Callback
        Route::get('callback/wordpress', 'Connections\WordpressController@callback')->name('wordpressCallback');

        // HubSpot Callback
        Route::get('callback/hubspot', 'Connections\HubspotController@callback')->name('hubspotCallback');

        // Mailchimp Callback
        Route::get('callback/mailchimp', 'Connections\MailchimpController@callback')->name('mailchimpCallback');
        Route::get('mailchimp/{connection}/lists', 'Connections\MailchimpController@getContentLists')->name('mailchimpLists');

        //LinkedIn callback
        Route::get('callback/linkedin', 'Connections\LinkedinController@callback')->name('linkedinCallback');

        //Dropbox Callback
        Route::get('callback/dropbox', 'Connections\DropboxController@callback')->name('dropboxCallback');

        //Google Callback
        Route::get('callback/google/{service}', 'Connections\GoogleController@callback')->name('googleCallback');

        // - Landing page for creating content
        Route::get('/create', 'ContentController@create')->name('contents.create');
        Route::post('/create/new', 'ContentController@store')->name('contents.store')->middleware('format_date:due_date,m/d/Y');

        // - create form page
        Route::get('/edit', 'ContentController@createContent')->name('content.create');
        Route::post('/edit', 'ContentController@editStore')->name('content.store');

        Route::post('/edit/images', 'ContentController@images')->name('imageContent');
        Route::post('/edit/attachments', 'ContentController@attachments')->name('attachmentContent');

        // Review page for client review and approval
        Route::get('/review/{content}', 'ContentController@reviewContent')->name('content.review');

        // - editing content form page
        Route::get('/edit/{content}', 'ContentController@editContent')->name('editContent');
        Route::post('/edit/{content}', 'ContentController@editStore')->name('content.update')->middleware('format_date:due_date,m/d/Y');

        Route::get('/onboarding', 'OnboardingController@index');

        Route::group(['prefix' => 'settings'], function () {
            Route::get('/', 'SettingsController@index')->name('settings.index');
            Route::post('/', 'SettingsController@update')->name('settings.update');
            Route::get('content', 'SettingsController@content')->name('content_settings.index');
            Route::get('buying', 'SettingsController@content')->name('buying_settings.index');

            Route::post('guidelines', 'GuidelineController@update')->name('guidelines.update');

            Route::get('email/verify/{confirmationCode}', 'SettingsController@verifyUserEmail');

            // Connection Routes
            Route::get('connections', 'SettingsController@connections')->name('connections.index');
            Route::post('connections/create', 'ConnectionController@store')->name('connections.store');
            Route::delete('connections/{connection}', 'ConnectionController@delete')->name('connections.destroy');

            Route::get('seo', 'SettingsController@seo')->name('seo_settings.index');

            Route::post('personas', 'Settings\PersonasController@create');
            Route::get('personas', 'Settings\PersonasController@index');
            Route::delete('personas/{persona}', 'Settings\PersonasController@delete');

            Route::post('buying_stages', 'Settings\BuyingStagesController@create');
            Route::get('buying_stages', 'Settings\BuyingStagesController@index');
            Route::delete('buying_stages/{buyingStage}', 'Settings\BuyingStagesController@delete');

            Route::get('account', 'AccountSettingsController@index')->name('settingsAccount');
            Route::post('account', 'AccountSettingsController@update');

            Route::group(['prefix' => 'subscription'], function () {
                Route::get('/', 'AccountSettingsController@showSubscription')->name('subscription');
                Route::post('/', 'AccountSettingsController@submitSubscription')->name('subscription.store');
                Route::get('clients', 'AccountSettingsController@showSubscriptionClients')->name('subscription-clients');
            });
        });

        Route::group(['prefix' => 'get_content_written'], function () {
            Route::get('categories', 'WriterAccessController@categories')->name('writeraccess_categories.index');

            Route::get('account', 'WriterAccessController@account')->name('writeraccess_accounts.show');

            Route::get('assetTypes', 'WriterAccessController@assetTypes')->name('writeraccess_types.index');

            Route::post('comment/{id}', 'WriterAccessController@postComment')->name('writeraccess_comments.store');

            Route::post('orders', 'WriterAccessController@createOrder')->name('writeraccess_orders.store');
            Route::get('orders', 'WriterAccessController@orders')->name('writeraccess_orders.index');
            Route::get('orders/{id}', 'WriterAccessController@orders')->name('writeraccess_orders.show');
            Route::delete('orders/{id}', 'WriterAccessController@deleteOrder')->name('writeraccess_orders.destroy');
            Route::post('orders/{writerAccessPartialOrder}/submit', 'WriterAccessController@orderSubmit')->name('writeraccess_orders.submit');

            Route::get('projects', 'WriterAccessController@projects')->name('writeraccess_projects.index');
            Route::get('projects/{id}', 'WriterAccessController@projects')->name('writeraccess_projects.show');
            Route::get('projects/create/{name}', 'WriterAccessController@createProject')->name('writeraccess_projects.store');

            Route::get('expertises', 'WriterAccessController@expertises')->name('writeraccess_expertises.index');
            Route::get('fees', 'WriterAccessPriceController@index')->name('writeraccess_fees.index');
            Route::get('fee', 'WriterAccessPriceController@fee')->name('writeraccess_fees.show');

            Route::get('bulk-order/{id}', 'WriterAccessBulkOrderStatusController@show')->name('writeraccess_bulkorders.show');
            Route::get('bulk-orders', 'WriterAccessBulkOrderStatusController@index')->name('writeraccess_bulkorders.index');
            Route::get('bulk-orders/status/{id}', 'WriterAccessBulkOrderStatusController@status')->name('writeraccess_bulkorders.status');
            Route::get('bulk-orders/sample', 'WriterAccessBulkOrderStatusController@sample')->name('writeraccess_bulkorders.sample');

            Route::post('partials', 'WriterAccessPartialOrderController@store')->name('writeraccess_partials.store')->middleware('format_date:due_date,m-d-Y');
            Route::post('partials/{id}', 'WriterAccessPartialOrderController@update')->name('writeraccess_partials.update');

            /**
             * Writer Access form pages.
             */
            Route::get('partials/order_setup/{writerAccessPartialOrder}', 'WriterAccessPartialOrderController@orderSetup')->name('orderSetup');
            Route::post('partials/upload/{writerAccessPartialOrder}', 'WriterAccessUploadController@store');
            Route::get('partials/order_audience/{writerAccessPartialOrder}', 'WriterAccessPartialOrderController@orderAudience')->name('orderAudience');
            Route::get('partials/order_review/{writerAccessPartialOrder}', 'WriterAccessPartialOrderController@orderReview')->name('orderReview');
        });

        Route::resource('writerAccessPrices', 'WriterAccessPriceController');
        Route::resource('writerAccessAssetTypes', 'WriterAccessAssetTypeController');


        Route::group(['prefix' => 'twitter'], function () {
            Route::get('followers', ['uses' => 'Connections\TwitterController@userSearch']);
        });

        Route::group(['prefix' => 'export'], function () {
            Route::get('content/{id}/{extension}', 'ExportController@content')->name('export.content');
            Route::get('order/{id}/{extension}', 'ExportController@order')->name('export.order');
        });


        /**
         * AJAX Helpers
         */
        Route::delete('/api/attachments/{attachment}', 'AttachmentController@destroy')->name('attachments.destroy');
        Route::get('/api/connections', 'ConnectionController@index');
        Route::get('/api/connections/ga', 'ConnectionController@ga');

        Route::get('/api/campaigns', 'CampaignController@index');
        Route::get('/api/campaigns/collaborators', 'CampaignCollaboratorsController@accountCollaborators');
        Route::get('/api/campaigns/{campaign}/collaborators', 'CampaignCollaboratorsController@index');
        Route::post('/api/campaigns/{campaign}/collaborators', 'CampaignCollaboratorsController@update');
        Route::get('/api/campaigns/{campaign}/tasks', 'CampaignTasksController@index');

        Route::get('/api/contents', 'ContentController@index');
        Route::get('/api/contents/published', 'ContentController@published');
        Route::get('/api/contents/ready', 'ContentController@ready');
        Route::get('/api/contents/written', 'ContentController@written');
        Route::get('/api/contents/{content}/collaborators', 'ContentCollaboratorsController@index');
        Route::post('/api/contents/{content}/collaborators', 'ContentCollaboratorsController@update');
        Route::get('/api/contents/{content}/guests', 'ContentGuestsController@index');
        Route::post('/api/contents/{content}/guests', 'ContentGuestsController@store');
        Route::get('/api/contents/{content}/tasks', 'ContentTasksController@index');
        Route::post('/api/contents/{content}/comments', 'ContentCommentsController@store');
        Route::get('/api/contents/{content}/review', 'ContentCommentsController@contentReviewData');
        Route::get('/api/contents/{content}/comments', 'ContentCommentsController@index');
        Route::get('/api/contents/{content}/approvals', 'ContentController@approvals');

        Route::get('/api/contents/{content}/messages', 'ContentMessageController@index');
        Route::post('/api/contents/{content}/messages', 'ContentMessageController@store');
        Route::post('/api/contents/{content}/messages/auth', 'ContentMessageController@auth');

        Route::get('/api/ideas', 'IdeaController@recent');
        Route::get('/api/ideas/collaborators', 'IdeaCollaboratorsController@index');
        Route::get('/api/ideas/{idea}/collaborators', 'IdeaCollaboratorsController@index');
        Route::post('/api/ideas/{idea}/collaborators', 'IdeaCollaboratorsController@update');

        Route::get('/api/activity_feed', 'ActivityController@index');

        Route::get('/api/account/members', 'AccountCollaboratorsController@index');
        Route::delete('/api/account/members/{id}', 'AccountCollaboratorsController@delete');
        Route::post('/api/account/disable', 'AccountController@disable');
        Route::get('/api/tasks', 'TaskController@index');
        Route::post('/api/trends/share/{connection}', 'ContentController@trendShare')->name('trendShare');
        Route::post('/search', 'SearchController@index')->name('search.index');
        Route::get('/api/content-types', 'ContentController@getContentTypes');

        Route::post('/pusher/auth', 'MessageController@auth')->name('pusher.auth');
        Route::get('/api/messages', 'MessageController@index')->name('messages.index');
        Route::post('/api/messages/{user}', 'MessageController@store')->name('messages.store');
        Route::post('/api/messages/{user}/mark_as_read', 'MessageController@markAsRead')->name('messages.mark_as_read');

        Route::get('/api/writeraccess-fetch-orders', 'WriterAccessOrdersController@fetch');
        Route::get('/api/content/orders', 'WriterAccessOrdersController@getOrders');
        Route::get('/api/content/orders-count', 'WriterAccessOrdersController@getOrdersCount');

        Route::get('/api/writeraccess-fetch-comments', 'WriterAccessCommentController@fetch');
        Route::get('/api/content/orders/{id}/comments', 'WriterAccessCommentController@getOrderComments');
        Route::post('/api/content/orders/{id}/comments', 'WriterAccessCommentController@postOrderComment');

        Route::get('/api/content-score/accounts', 'ContentScoreController@accounts');
        Route::get('/api/content-score/properties/{account_id}', 'ContentScoreController@properties');
        Route::get('/api/content-score/profiles/{account_id}/{property_id}', 'ContentScoreController@profiles');
        Route::get('/api/content-score/{profile_id}', 'ContentScoreController@score');
    });
});

Route::get('/coming-soon',  function () {
    return view('coming-soon', ['name' => 'James']);
});

Route::post('/stripe-webhook', 'StripeController@webhook');



/*
 |--------------------------------------------------------------------------
 | Administrative Dashboard routes
 |--------------------------------------------------------------------------
 |
 | Here are the routes for the admin back-end of the site.
 |
*/
Route::group([ 'prefix' => 'admin' ], function() {
    Route::post('login', 'Admin\LoginController@login')->name('admin.login.login');
    Route::get('login', 'Admin\LoginController@getLogin')->name('admin.login.show');

    Route::group([ 'middleware' => 'admins_only' ], function() {
        Route::get('dashboard', 'Admin\DashboardController@index')->name('admin.dashboard.index');

        Route::get('users', 'Admin\UserController@index')->name('admin.users.index');

        Route::get('accounts', 'Admin\AccountController@index')->name('admin.accounts.index');
        Route::get('accounts/{account}', 'Admin\AccountController@show')->name('admin.accounts.show');

        Route::get('accounts/{account}/edit', 'Admin\AccountController@edit')->name('admin.accounts.edit');
        Route::post('accounts/{account}/edit', 'Admin\AccountController@update')->name('admin.accounts.update');

        Route::post('subscriptions/{account}/create', 'Admin\AccountController@storeSubscription')->name('admin.account_subscriptions.store')
            ->middleware('format_date:expiration_date,m/d/Y')
            ->middleware('format_date:start_date,m/d/Y');
    });
});
