launch.module.controller('UserController', [
	'$scope', '$location', 'UserService', 'AuthService', 'SessionService', 'NotificationService', function ($scope, $location, userService, authService, sessionService, notificationService) {
		var self = this;

		self.loggedInUser = null;

		self.init = function () {
			self.loggedInUser = authService.userInfo();
			$scope.refreshMethod();
		};

		$scope.user = null;
		$scope.isLoading = false;

		$scope.refreshMethod = function () {
			$scope.isLoading = true;


			$scope.user = userService.get(self.loggedInUser.id, self.loggedInUser.account.id, {
				success: function(r) {
					$scope.isLoading = false;
				},
				error: function (r) {
                    debugger
					$scope.isLoading = false;
					launch.utils.handleAjaxErrorResponse(r, notificationService);
				}
			});
		};

		self.init();
	}
]);
