launch.module.controller('UserController', [
	'$scope', '$location', 'UserService', 'AuthService', 'SessionService', 'NotificationService', function ($scope, $location, userService, authService, sessionService, notificationService) {
		var self = this;

		self.loggedInUser = null;

		self.init = function () {
			self.loggedInUser = authService.userInfo();
			$scope.refreshMethod();
		};

		$scope.user = null;

		$scope.refreshMethod = function () {
			$scope.user = userService.get(self.loggedInUser.id, {
				success: function (r) { },
				error: function(r) {
					launch.utils.handleAjaxErrorResponse(r, notificationService);
				}
			});
		};

		$scope.afterSaveSuccess = function (user, form) {
			$scope.user = user;

			sessionService.set(sessionService.AUTHENTICATED_KEY, $scope.user);
		};

		self.init();
	}
]);
