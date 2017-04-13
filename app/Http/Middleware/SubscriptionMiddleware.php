<?php

namespace App\Http\Middleware;

use App\Account;
use Closure;

class SubscriptionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        Account::selectedAccount()->ensureAccountHasSubscription();

        return $next($request);
    }
}
