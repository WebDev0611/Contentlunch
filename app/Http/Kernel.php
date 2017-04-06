<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{

    public function handle($request)
    {
        try
        {
            return parent::handle($request);
        }
        catch(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e)
        {
            return response()->view('errors.404', [], 404);
        }
        catch (Exception $e)
        {
            $this->reportException($e);

            return $this->renderException($request, $e);
        }
    }

    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
        ],

        'api' => [
            'throttle:60,1',
        ],

        'fw-block-bl' => [
            \PragmaRX\Firewall\Middleware\FirewallBlacklist::class,
        ],
        'fw-allow-wl' => [
            \PragmaRX\Firewall\Middleware\FirewallWhitelist::class,
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'can' => \App\Http\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'role' => \Zizaco\Entrust\Middleware\EntrustRole::class,
        'permission' => \Zizaco\Entrust\Middleware\EntrustPermission::class,
        'ability' => \Zizaco\Entrust\Middleware\EntrustAbility::class,
        'calendar' => \App\Http\Middleware\CalendarMiddleware::class,
        'format_date' => \App\Http\Middleware\ConvertDateFormat::class,
        'format_datetime' => \App\Http\Middleware\ConvertDatetimeFormat::class,
    ];
}
