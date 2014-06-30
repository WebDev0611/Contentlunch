launch.module.controller('ConfirmController', [
	'$scope', '$location', '$route', '$routeParams', 'AuthService', 'UserService', 'NotificationService', function($scope, $location, $route, $routeParams, authService, userService, notificationService) {
		var self = this;

		self.init = function () {
			authService.logout();
			authService.confirm($route.current.params.code, {
				success: function(r) {
					$scope.isDisabled = false;
					$scope.selectedUser = r;
					$scope.isLoaded = true;
				},
				error: function(r) {
					$location.path('/login');
					$location.search('link', 'expired');
				}
			});
		};

		$scope.isLoaded = false;
		$scope.isSaving = false;
		$scope.isConfirmUser = true;
		$scope.isDisabled = false;
		$scope.selectedUser = null;
		$scope.confirmPasswordError = null;
		$scope.changePassword = {
			currentPassword: null,
			newPassword: null,
			confirmPassword: null
		};

		$scope.changePassword = function(e) {
			if (e.type === 'keypress' && e.charCode !== 13) {
				return;
			}

			var msg = '';

			msg += (launch.utils.isBlank($scope.changePassword.newPassword) ? 'New Password is required.' : launch.utils.validatePassword($scope.changePassword.newPassword)) + '\n';
			msg += launch.utils.isBlank($scope.changePassword.confirmPassword) ? 'Confirm Password is required.\n' : (($scope.changePassword.newPassword !== $scope.changePassword.confirmPassword) ? 'Passwords do not match.\n' : '');

			if (launch.utils.isBlank(msg)) {
				$scope.selectedUser.password = $scope.changePassword.newPassword;
				$scope.selectedUser.passwordConfirmation = $scope.changePassword.confirmPassword;
				$scope.isSaving = true;

				userService.update($scope.selectedUser, {
					success: function(r) {
						$scope.isSaving = false;
						notificationService.success('Success!', 'You have successfully changed your password!');

						$route.path('/login');

						//authService.login($scope.selectedUser.email, $scope.selectedUser.password, false, {
						//	success: function() {
						//		$location.path('/');
						//		// Completely reload everything
						//		$route.reload();
						//	},
						//	error: function(res) { }
						//});
					},
					error: function(r) {
						$scope.isSaving = false;
						launch.utils.handleAjaxErrorResponse(r, notificationService);
					}
				});
			} else {
				notificationService.error('Error!', '' + msg);
			}
		};

		self.init();
	}
]);