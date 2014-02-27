
launch.module.controller('UserController', ['$scope', '$location', 'UserService', 'AuthService', function ($scope, $location, UserService, AuthService) {
	$scope.user = {};
  $scope.serverUser = {};
  var info = AuthService.userInfo();
	UserService.get({ id: info.id }, function (user) {
		$scope.user = user;
	});
  AuthService.getCurrentUser(function(user) {
    $scope.serverUser = user;
  });
}]);
