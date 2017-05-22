<?php

namespace App\Providers;

use App\Account;
use App\Campaign;
use App\Services\AccountService;
use App\Services\CampaignService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class ModelServicesProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('accountService', function($app) {
            return new AccountService($app->make(Account::class));
        });

        $this->app->bind('contentService', function($app) {
            return new ContentService($app->make(Content::class));
        });

        $this->app->bind('campaignService', function($app) {
            return new CampaignService($app->make(Campaign::class));
        });
    }
}
