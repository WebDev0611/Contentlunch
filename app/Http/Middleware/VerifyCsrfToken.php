<?php

namespace App\Http\Middleware;

use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;


use Closure;
use Illuminate\Session\TokenMismatchException;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'edit/images',
        'edit/attachments',
        'writeraccess/orders/*/submit'
    ];


    public function handle($request, Closure $next){

        if (env("BYPASS_CSRF_CHECK", false)) {
            return $next($request);
        }

        return parent::handle($request, $next);
    }
}
