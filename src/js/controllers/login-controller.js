launch.module.controller('LoginController', [
	'$scope', '$sanitize', '$location', 'AuthService', 'NotificationService', function ($scope, $sanitize, $location, authService, notificationService) {
		var self = this;

		self.init = function() {
			authService.logout();
			$scope.toggleMode();

			$scope.user = new launch.Authentication();

			self.redirect = launch.utils.isBlank($location.search()['path']) ? null : $location.search()['path'];
		};

		self.redirect = null;

		$scope.user = null;
		$scope.hasError = launch.utils.isPropertyValid;
		$scope.errorMessage = launch.utils.getPropertyErrorMessage;
		$scope.forceDirty = false;
		$scope.isSaving = false;
		$scope.mode = null;

		$scope.login = function(e, user) {
			if (e.type === 'keypress' && e.charCode !== 13) {
				return;
			}

			$scope.forceDirty = true;

			var msg = launch.utils.validateAll($scope.user);

			if (!launch.utils.isBlank(msg)) {
				notificationService.error('Error!', 'Please fix the following problems:\n\n' + msg.join('\n'));

				return;
			}

			$scope.isSaving = true;

			authService.login($scope.user.email, $scope.user.password, $scope.user.remember, {
				success: function(u) {
					$scope.isSaving = false;

					if (u.role.isGlobalAdmin === true) {
						$location.path('/accounts');
					} else {
						$location.path(launch.utils.isBlank(self.redirect) ? '/' : self.redirect).search({ });
					}
				},
				error: function (r) {
					$scope.isSaving = false;

					launch.utils.handleAjaxErrorResponse(r, notificationService);
				}
			});
		};

		$scope.toggleMode = function(mode) {
			$scope.mode = launch.utils.isBlank(mode) ? 'Login' : mode;
		};

		$scope.retreievePassword = function(e, user) {
			if (e.type === 'keypress' && e.charCode !== 13) {
				return;
			}

			authService.forgotPassword(user.email, {
				success: function(r) {
					notificationService.success('Message Sent!', r.message);
				},
				error: function(r) {
					launch.utils.handleAjaxErrorResponse(r, notificationService);
				}
			});
		};

		self.init();
	}
]);