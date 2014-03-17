launch.module.controller('UserController', [
	'$scope', '$location', 'UserService', 'AuthService', 'SessionService', function ($scope, $location, userService, authService, sessionService) {
		var self = this;

		self.init = function() {
			$scope.refreshMethod();
		};

		$scope.user = null;

		$scope.refreshMethod = function () {
			$scope.user = authService.userInfo();

			if (!$scope.user) {
				$location.path('/login');
				return;
			}
		};

		$scope.afterSaveSuccess = function (user, form) {
			$scope.user = user;

			sessionService.set(sessionService.AUTHENTICATED_KEY, $scope.user);
		};

		self.init();
	}
]);
