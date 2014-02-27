
launch.module.controller('UserController', [
	'$scope', '$location', 'UserService', 'AuthService', function($scope, $location, UserService, AuthService) {
		$scope.user = { };
		$scope.serverUser = { };

		var info = AuthService.userInfo();

		$scope.user = UserService.get({ id: info.id });

		AuthService.getCurrentUser(function (user) {
			$scope.serverUser = user;
		});
	}
]);
