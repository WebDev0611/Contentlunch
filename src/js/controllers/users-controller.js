launch.module.controller('UsersController', [
	'$scope', '$location', 'UserService', function ($scope, $location, UserService) {
		$scope.title = 'This is the users page controller';
    $scope.users = [];
    UserService.query(function (users) {
      $scope.users = users;
    });
	}
]);
