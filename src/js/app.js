(function(window, angular) {
	'use strict';

	launch = window.launch || (window.launch = { });
	launch.module = angular.module('launch', ['ngRoute', 'ngResource', 'ngSanitize', 'ui.bootstrap', 'angularFileUpload', 'localytics.directives']);

	launch.module.config([
			'$routeProvider', '$locationProvider', '$httpProvider', function ($routeProvider, $locationProvider, $httpProvider) {
				$locationProvider.html5Mode(true);
				$routeProvider
					.when('/', {
						controller: 'HomeController',
						templateUrl: '/assets/views/home.html'
					})
					.when('/login', {
						controller: 'LoginController',
						templateUrl: '/assets/views/login.html'
					})
					.when('/account', {
						controller: 'AccountController',
						templateUrl: '/assets/views/account.html'
					})
					.when('/account/connections', {
						controller: 'ContentConnectionsController',
						templateUrl: '/assets/views/content-connections.html'
					})
					.when('/account/content-settings', {
						controller: 'ContentSettingsController',
						templateUrl: '/assets/views/content-settings.html'
					})
					.when('/user', {
						controller: 'UserController',
						templateUrl: '/assets/views/user.html'
					})
					.when('/user/confirm/:code', {
						controller: 'ConfirmController',
						templateUrl: '/assets/views/reset-password.html'
					})
					.when('/users', {
						controller: 'UsersController',
						templateUrl: '/assets/views/users.html'
					})
					.when('/roles', {
						controller: 'RolesController',
						templateUrl: '/assets/views/roles.html'
					})
					.when('/consult', {
						controller: 'ConsultController',
						templateUrl: '/assets/views/consult.html'
					})
					.when('/create', {
						controller: 'CreateController',
						templateUrl: '/assets/views/create.html'
					})
					.when('/collaborate', {
						controller: 'CollaborateController',
						templateUrl: '/assets/views/collaborate.html'
					})
					.when('/calendar', {
						controller: 'CalendarController',
						templateUrl: '/assets/views/calendar.html'
					})
					.when('/launch', {
						controller: 'LaunchController',
						templateUrl: '/assets/views/launch.html'
					})
					.when('/measure', {
						controller: 'MeasureController',
						templateUrl: '/assets/views/measure.html'
					})
					.when('/accounts', {
						controller: 'AccountsController',
						templateUrl: '/assets/views/accounts.html'
					})
					.when('/subscription', {
						controller: 'SubscriptionController',
						templateUrl: '/assets/views/subscription.html'
					})
					.otherwise({
						redirectTo: '/'
					});

				var interceptor = [
					'$location', '$q', function($location, $q) {
						var success = function(r) {
							return r;
						}

						var error = function(r) {
							if (r.status === 401) {
								// TODO: OPEN DIALOG HERE!!
								$location.path('/login');
								return $q.reject(r);
							} else {
								return $q.reject(r);
							}
						}

						return function(promise) {
							return promise.then(success, error);
						}
					}
				];

				$httpProvider.responseInterceptors.push(interceptor);
			}
		])
		.run([
			'$rootScope', '$location', 'UserService', 'AuthService', function($rootScope, $location, userService, authService) {
				$rootScope.$on('$routeChangeStart', function (event, next, current) {
					if ($location.path() === '/login') {
						authService.logout();
					} else if ($location.path() === '/reset') {

					} else if ($location.path().indexOf('/user/confirm') === 0) {

					} else if (!authService.isLoggedIn()) {
						$location.path('/login');
					}
				});

				$.pnotify.defaults.styling = "bootstrap3";
				$.pnotify.defaults.history = false;

				// TODO: FIGURE OUT IF WE CAN SET THIS FROM THE SERVER!!!
				launch.config.DEBUG_MODE = true;
			}
		]);

	launch.module.filter('pageStart', function() {
		return function(input, start) {
			return input.splice(parseInt(start));
		};
	});
})(window, angular);
