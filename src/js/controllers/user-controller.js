launch.module.controller('UserController', [
	'$scope', '$location', 'UserService', 'AuthService', function($scope, $location, userService, authService) {
		var self = this;

		self.init = function() {
			$scope.refreshMethod();
		};

		$scope.user = null;
		$scope.serverUser = null;

		$scope.refreshMethod = function () {
			var info = authService.userInfo();

			$scope.user = userService.get({ id: info.id });

			authService.getCurrentUser(function (user) {
				$scope.serverUser = user;
			});
		};

		$scope.afterSaveSuccess = function (r, form) {

		};

		self.init();
	}
]);
