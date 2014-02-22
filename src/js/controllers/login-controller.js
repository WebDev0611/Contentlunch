
launch.module.controller('LoginController', [
	'$scope', '$http', '$resource', function ($scope, $http, $resource) {
		$scope.title = 'This is the login controller';
		$scope.user = {};
		$scope.emailError = null;
		$scope.passwordError = null;

		$scope.validateLogin = function (user) {
			if (launch.utils.isBlank(user.email)) {
				$scope.emailError = 'Please enter your email address.';
			} else if (!user.email.match(launch.config.EMAIL_ADDRESS_REGEX)) {
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

		$scope.login = function (user) {
			if (!$scope.validateLogin(user)) {
				return false;
			}

			return true;
		};
	}
]);
