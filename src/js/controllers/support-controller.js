launch.module.controller('SupportController', [
	'$scope', '$location', 'AuthService', 'AccountService', 'NotificationService', function ($scope, $location, authService, accountService, notificationService) {
		var self = this;

		self.loggedInUser = null;

		self.ajaxHandler = {
			success: function (r) {
				$scope.isSaving = false;
				notificationService.success('SUCCESS!!', 'You\'re message has been sent! We\'ll be in contact soon.');
			},
			error: function (r) {
				$scope.isSaving = false;
				launch.utils.handleAjaxErrorResponse(r, notificationService);
			}
		};

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

			var email = {
				email: $scope.email,
				name: $scope.name,
				company: $scope.company,
				module: $scope.module,
				problem: $scope.description
			};

			$scope.isSaving = true;

			accountService.sendSupportEmail(email, self.ajaxHandler);
		};

		self.init();
	}
]);