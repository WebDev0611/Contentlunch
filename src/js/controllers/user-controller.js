
launch.module.controller('UserController', ['$scope', '$location', 'UserService', function ($scope, $location, UserService) {
	$scope.user = {};
	UserService.get(function (user) {
		$scope.user = user;
	});
}]);
