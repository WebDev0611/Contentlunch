<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class RequiresAdminPrivileges
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
        $isAjax = $request->ajax() || $request->wantsJson();

        if (!Auth::check() || !Auth::user()->isAdmin()) {
            session([ 'redirect_url' => $request->getRequestUri() ]);

            if ($isAjax) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->route('admin.login.show');
            }
        }

        return $next($request);
    }
}
