<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Authenticate
{
    /**
     * List of URLs exempt from authentication.
     */
    protected $allowedUrls = [
        'signup',
        'signup/invite'
    ];

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
        $isUrlAllowed = $this->isUrlAllowed($request->path());

        if (Auth::guard($guard)->guest()) {
            if (($request->ajax() || $request->wantsJson()) && !$isUrlAllowed) {
                return response('Unauthorized.', 401);
            } else {
                if (!$isUrlAllowed) {
                    return redirect()->guest('login');
                }
            }
        }

        return $next($request);
    }

    protected function isUrlAllowed($requestUrl)
    {
        $regex = '#' . implode('|', $this->allowedUrls) . '#';

        return (boolean) preg_match($regex, $requestUrl);
    }
}