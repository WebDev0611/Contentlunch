<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

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
            session([ 'redirect_url' => $request->getRequestUri() ]);

            if ($isAjax) {
                return response('Unauthorized.', 401);
            } else {
                return redirect('/login');
            }
        }

        Session::set('qebotUser', Auth::user()->qebot_user);

        return $next($request);
    }
}
