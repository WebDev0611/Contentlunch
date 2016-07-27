<?php

namespace App\Providers;

use Blade;
use User;
use Illuminate\Support\ServiceProvider;

class AccountServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return bool
     */
    public function boot()
    {
        //should have more directives for all account info
        Blade::directive('is_agency', function() {
            //needs to have the account info
            $test = User;
            return true;
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
