<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Idea' => 'App\Policies\IdeaPolicy',
        'App\Content' => 'App\Policies\ContentPolicy',
        'App\Calendar' => 'App\Policies\CalendarPolicy',
        'App\Campaign' => 'App\Policies\CampaignPolicy',
        'App\Account' => 'App\Policies\AccountPolicy',
        'App\Task' => 'App\Policies\TaskPolicy',
        'App\WriterAccessOrder' => 'App\Policies\ContentOrderPolicy',
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        $gate->define('guests-denied', function($user) {
            return !$user->isGuest();
        });
    }
}
