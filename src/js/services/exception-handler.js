if(window.location.hostname.indexOf('local') == -1) {
    // Only load up the exception handler when not running on dev servers.
    launch.module.factory('$exceptionHandler', function ($window, $injector) {
        return function (exception, cause) {
            var AuthService = $injector.get('AuthService');
            var SessionService = $injector.get('SessionService');
            var NotificationService = $injector.get('NotificationService');
            var $location = $injector.get('$location');

            var log = {
                message: exception.message,
                stack: exception.stack,
                cause: cause,
                userInfo: AuthService.userInfo,
                location: $location.url(),
                session: SessionService.dump(),
                userAgent: $window.navigator.userAgent
            };


            $.post('/api/log_error', {log: JSON.stringify(log)});

            NotificationService.error('Unexpected Error', 'Whoops, looks like something went wrong. <br/>The ContentLaunch support team has been notified.', 10000);

            console.error(exception);

            $location.path('/');
        };
    });
}