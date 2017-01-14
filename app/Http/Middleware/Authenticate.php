<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $isAjax = $request->ajax() || $request->wantsJson();

        if (Auth::guard($guard)->guest()) {
            if ($isAjax) {
                return response('Unauthorized.', 401);
            } else {
                return redirect('/login');
            }
        }

        return $next($request);
    }
}
