(function (window, angular) {
	'use strict';

	launch = window.launch || (window.launch = { });
	launch.activeMenu = null;
	launch.module = angular.module('launch', ['ngRoute', 'ngResource', 'ngSanitize']);

	launch.module.config([
		'$routeProvider', '$locationProvider', '$resourceProvider', function ($routeProvider, $locationProvider, $resource) {
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
				.otherwise({
					redirectTo: '/'
				});
		}
	])
	.run(['$rootScope', '$location', 'UserService', 'AuthService', function ($rootScope, $location, userService, authService) {
		$rootScope.$on('$routeChangeStart', function (event, next, current) {
			if ($location.path() === '/login') {
				authService.logout();
			} else if (!authService.isLoggedIn()) {
				$location.path('/login');
			}
		});
	}]);
})(window, angular);