(function(window, angular) {
	'use strict';

	launch = window.launch || (window.launch = { });
	launch.module = angular.module('launch', ['ngRoute', 'ngResource', 'ngSanitize', 'ui.bootstrap', 'angularFileUpload', 'ui.tinymce', 'ui.select2']);

	launch.module.config([
			'$routeProvider', '$locationProvider', '$httpProvider', function($routeProvider, $locationProvider, $httpProvider) {
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
					.when('/account/seo', {
						controller: 'SeoSettingsController',
						templateUrl: '/assets/views/seo-settings.html'
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
					.when('/create/concept/create', {
						controller: 'CreateConceptController',
						templateUrl: '/assets/views/concept-form.html'
					})
					.when('/create/concept/edit/:conceptId', {
						controller: 'ConceptController',
						templateUrl: '/assets/views/concept-form.html'
					})
					.when('/create/content/create', {
						controller: 'CreateContentController',
						templateUrl: '/assets/views/content-form.html'
					})
					.when('/create/content/edit/:contentId', {
						controller: 'ContentController',
						templateUrl: '/assets/views/content-form.html'
					})
					.when('/create/content/view/:contentId', {
						controller: 'ContentController',
						templateUrl: '/assets/views/content-view.html'
					})
					.when('/create/content/launch/:contentId', {
						controller: 'ContentController',
						templateUrl: '/assets/views/content-launch.html'
					})
					.when('/create/content/promote/:contentId', {
						controller: 'ContentController',
						templateUrl: '/assets/views/content-promote.html'
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
						var success = function (r) {
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
			'$rootScope', '$location', 'UserService', 'AuthService', 'NotificationService', function ($rootScope, $location, userService, authService, notificationService) {
				var path = $location.path();

				authService.fetchCurrentUser({
					success: function (r) {
						if (!r.id && $location.path() !== '/login') {
							$location.path('/login').search('path', path);
						}
					}
				});

				$rootScope.$on('$routeChangeStart', function (event, next, current) {
					// TODO: VALIDATE THAT THE USER IS ALLOWED TO VIEW THE PAGE THEY ARE REQUESTING!! IF NOT, SHOW A WARNING OR ERROR AND REDIRECT TO HOME!!
					if ($location.path() === '/login') {
						authService.logout();
					} else if ($location.path() === '/reset') {

					} else if ($location.path().indexOf('/user/confirm') === 0) {

					} else if (!authService.isLoggedIn()) {
						$location.path('/login').search('path', path);
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
