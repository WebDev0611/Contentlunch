(function (window, angular) {
'use strict';

launch = window.launch || (window.launch = {});
launch.module = angular.module('launch', [
	'ngRoute', 'ngResource'
]);
launch.module.config(['$routeProvider', '$locationProvider', '$resourceProvider', function ($routeProvider, $locationProvider, $resource) {
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
		.otherwise({
			redirectTo: '/'
		})
	;
}]);

})(window, angular);
