launch.module.controller('LoginController', [
	'$scope', '$sanitize', '$location', 'AuthService', 'NotificationService', function ($scope, $sanitize, $location, authService, notificationService) {
		$scope.title = 'This is the login controller';
		$scope.user = { };
		$scope.emailError = null;
		$scope.passwordError = null;
		$scope.user = { };

		authService.logout();

		$scope.validateLogin = function(user) {
			if (launch.utils.isBlank(user.email)) {
				$scope.emailError = 'Please enter your email address.';
			} else if (!launch.utils.isValidEmail(user.email)) {
				$scope.emailError = 'Please enter a valid email address.';
			} else {
				$scope.emailError = null;
			}

			if (launch.utils.isBlank(user.password)) {
				$scope.passwordError = 'Please enter your password.';
			} else {
				$scope.passwordError = null;
			}

			return (launch.utils.isBlank($scope.emailError) && launch.utils.isBlank($scope.passwordError));
		};

		$scope.login = function (e, user) {
			if (e.type === 'keypress' && e.charCode !== 13) {
				return;
			}

			if (!$scope.validateLogin(user)) {
				return;
			}

			authService.login($scope.user.email, $scope.user.password, $scope.user.remember, {
				success: function(r) {
					$location.path('/');
				},
				error: function(r) {
					launch.utils.handleAjaxErrorResponse(r, notificationService);
				}
			});
		};
	}
]);