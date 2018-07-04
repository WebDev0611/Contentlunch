<?php

namespace App\Http\Middleware;

use Closure;

class Authorize extends \Illuminate\Foundation\Http\Middleware\Authorize
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $ability
     * @param  string|null  $model
     * @return mixed
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function handle($request, Closure $next, $ability, $model = null)
    {
        $isAjax = $request->ajax() || $request->wantsJson();

        if ($this->gate->denies($ability, $this->getGateArguments($request, $model))) {
            if ($isAjax) {
                return response()->json(['error' => 'User not authorized'], 403);
            } else {
                abort(404);
            }
        }

        return $next($request);
    }
}