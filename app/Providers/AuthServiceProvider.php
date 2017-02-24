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
        \App\Idea::class => \App\Policies\IdeaPolicy::class,
        \App\Content::class => \App\Policies\ContentPolicy::class,
        \App\Calendar::class => \App\Policies\CalendarPolicy::class,
        \App\Campaign::class => \App\Policies\CampaignPolicy::class,
        \App\Account::class => \App\Policies\AccountPolicy::class,
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
    }
}
