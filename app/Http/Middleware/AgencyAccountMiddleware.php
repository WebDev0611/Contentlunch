<?php

namespace App\Http\Middleware;

use App\Account;
use Closure;
use Illuminate\Support\Facades\App;

class AgencyAccountMiddleware
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
        // Check if selected account is agency account
        $account = Account::selectedAccount();

        if (!$account->isAgencyAccount() && !$account->isSubAccount()) {
            App::abort(404);
        }

        return $next($request);
    }
}
