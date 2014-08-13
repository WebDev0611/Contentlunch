launch.module.controller('SupportController', [
	'$scope', '$location', 'AuthService', 'NotificationService', function ($scope, $location, authService, notificationService) {
		var self = this;

		self.loggedInUser = null;

		self.init = function() {
			self.loggedInUser = authService.userInfo();

			$scope.email = self.loggedInUser.email;
			$scope.name = self.loggedInUser.displayName;
			$scope.company = self.loggedInUser.account.title;
			$scope.modules = $.map(self.loggedInUser.modules, function(m) { return launch.utils.titleCase(m.title); });
		};

		$scope.email = null;
		$scope.name = null;
		$scope.company = null;
		$scope.module = null;
		$scope.description = null;
		$scope.modules = null;
		$scope.isSaving = false;


		$scope.sendMessage = function() {
			var msg = '';

			msg += (launch.utils.isBlank($scope.email)) ? 'Email Address is required.\n' : (!launch.utils.isValidEmail($scope.email) ? 'Please enter a valid Email Address.\n' : '');
			msg += (launch.utils.isBlank($scope.name)) ? 'Name is required.\n' : '';
			msg += (launch.utils.isBlank($scope.company)) ? 'Company is required.\n' : '';
			msg += (launch.utils.isBlank($scope.module)) ? 'Module is required.\n' : '';
			msg += (launch.utils.isBlank($scope.description)) ? 'Description is required.\n' : '';

			if (!launch.utils.isBlank(msg)) {
				notificationService.error('Error!!', 'Please fix the following problems:\n\n' + msg);
				return;
			}


		};

		self.init();
	}
]);