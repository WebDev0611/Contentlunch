
launch.module.controller('LoginController', ['$scope', '$sanitize', '$location', 'AuthService', function($scope, $sanitize, $location, AuthService) {
  $scope.title = 'This is the login controller';
  $scope.user = {};
  $scope.login = function () {
  	AuthService.save({
  		'email': $sanitize($scope.user.email),
  		'password': $sanitize($scope.user.password)
  	}, function () {
  		$scope.flash = '';
  		$location.path('/');
  		sessionStorage.authenticated = true;
  	}, function (response) {
  		$scope.flash = response.data.flash;
  	});
  };
}]);
