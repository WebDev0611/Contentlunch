(function(window, angular) {
	'use strict';

	launch = window.launch || (window.launch = { });
	launch.activeMenu = null;
	launch.module = angular.module('launch', ['ngRoute', 'ngResource', 'ngSanitize', 'ui.bootstrap']);

	launch.module.config([
			'$routeProvider', '$locationProvider', '$resourceProvider', '$tooltipProvider', function($routeProvider, $locationProvider, $resource, $tooltipProvider) {
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
					.when('/accounts', {
						controller: 'AccountsController',
						templateUrl: '/assets/views/accounts.html'
					})
					.when('/user', {
						controller: 'UserController',
						templateUrl: '/assets/views/user.html'
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
					.otherwise({
						redirectTo: '/'
					});
			}
		])
		.run([
			'$rootScope', '$location', 'UserService', 'AuthService', function($rootScope, $location, userService, authService) {
				$rootScope.$on('$routeChangeStart', function(event, next, current) {
					if ($location.path() === '/login') {
						authService.logout();
					} else if (!authService.isLoggedIn()) {
						$location.path('/login');
					}
				});

				$.pnotify.defaults.styling = "bootstrap3";
				$.pnotify.defaults.history = false;
			}
		]);

	launch.module.filter('pageStart', function() {
		return function(input, start) {
			return input.splice(parseInt(start));
		};
	});
})(window, angular);