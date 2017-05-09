<?php

namespace App\Providers;

use App\Account;
use App\Services\AccountService;
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
    }
}
