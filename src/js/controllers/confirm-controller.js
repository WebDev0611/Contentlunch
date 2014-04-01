launch.module.controller('ConfirmController', [
	'$scope', '$location', '$route', '$routeParams', 'AuthService', 'UserService', 'NotificationService', function ($scope, $location, $route, $routeParams, authService, userService, notificationService) {
		var self = this;

		self.init = function () {
			authService.confirm($route.current.params.code, {
				success: function (r) {
					$scope.isDisabled = false;
					$scope.selectedUser = r;
				},
				error: function (r) {
					$scope.isDisabled = true;
					launch.utils.handleAjaxErrorResponse(r, notificationService);
				}
			});
		};

		$scope.newPassword = null;
		$scope.confirmPassword = null;
		$scope.isSaving = false;
		$scope.isConfirmUser = true;
		$scope.isDisabled = false;
		$scope.selectedUser = null;
		$scope.newPasswordError = null;
		$scope.confirmPasswordError = null;

		$scope.changePassword = function(e) {
			if (e.type === 'keypress' && e.charCode !== 13) {
				return;
			}

			$scope.newPasswordError = launch.utils.isBlank($scope.newPassword) ? 'New Password is required.' : launch.utils.validatePassword($scope.newPassword);
			$scope.confirmPasswordError = launch.utils.isBlank($scope.confirmPassword) ? 'Confirm Password is required.' : (($scope.newPassword !== $scope.confirmPassword) ? 'Passwords do not match.' : null);

			if (launch.utils.isBlank($scope.newPasswordError) && launch.utils.isBlank($scope.confirmPasswordError)) {
				$scope.selectedUser.password = $scope.newPassword;
				$scope.selectedUser.passwordConfirmation = $scope.confirmPassword;
				$scope.isSaving = true;

				userService.update($scope.selectedUser, {
					success: function (r) {
						$scope.isSaving = false;
						notificationService.success('Success!', 'You have successfully changed your password!');

						authService.login($scope.selectedUser.userName, $scope.selectedUser.password, false, {
							success: function() {
								$location.path('/home');
							},
							error: function(res) { }
						});
					},
					error: function (r) {
						$scope.isSaving = false;
						launch.utils.handleAjaxErrorResponse(r, notificationService);
					}
				});
			}
		};

		self.init();
	}
]);