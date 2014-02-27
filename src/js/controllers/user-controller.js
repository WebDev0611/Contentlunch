
launch.module.controller('UserController', ['$scope', '$location', 'UserService', 'AuthService', function ($scope, $location, UserService, AuthService) {
	$scope.user = {};
  var info = AuthService.userInfo();
	UserService.get({ id: info.id }, function (user) {
		$scope.user = user;
	});
}]);
