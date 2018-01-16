<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\App;
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

        $is_qebot_user = Auth::user() ? (Auth::user()->qebot_user || $this->isQebotDomain($request)) : $this->isQebotDomain($request) ;

        Session::set('qebotUser', $is_qebot_user);

        if($is_qebot_user){
            App::setLocale("qb");
        }

        if (Auth::guard($guard)->guest()) {
            session([ 'redirect_url' => $request->getRequestUri() ]);

            if ($isAjax) {
                return response('Unauthorized.', 401);
            } else {
                return redirect('/login');
            }
        }

        return $next($request);
    }

    private function isQebotDomain(Request $request){
        return strpos($request->getHttpHost(), "qebot");
    }
}
