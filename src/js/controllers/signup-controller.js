launch.module.controller('SignupController', [
	'$scope', '$filter', '$location', 'AuthService', 'AccountService', 'UserService', 'NotificationService', 'SessionService', function ($scope, $filter, $location, authService, accountService, userService, notificationService, sessionService) {
		var self = this;

		self.subscription = null;

		self.ajaxHandler = {
			success: function (r) {

			},
			error: function (r) {
				$scope.isSaving = false;
				launch.utils.handleAjaxErrorResponse(r, notificationService);
			}
		};

		self.init = function () {
			$scope.user = {
				firstName: null,
				lastName: null,
				title: null,
				businessName: null,
				phoneNumber: null,
				emailAddress: null
			};

			self.subscription = accountService.getSubscriptions({
				success: function(r) {
					if (self.subscription.length > 0) {
						self.subscription = $.grep(self.subscription, function (s) { return s.subscriptionLevel === 3; });

						if (self.subscription.length === 1) {
							self.subscription = self.subscription[0];
						}
					}
				},
				error: self.ajaxHandler.error
			});
		};

		$scope.user = null;
		$scope.isSaving = false;

		$scope.signUp = function() {
			var msg = '';

			if (launch.utils.isBlank($scope.user.firstName)) { msg += '\nFirst Name is required.'; }
			if (launch.utils.isBlank($scope.user.lastName)) { msg += '\nLast Name is required.'; }
			//if (launch.utils.isBlank($scope.user.title)) { msg += '\nTitle is required.'; }
			if (launch.utils.isBlank($scope.user.businessName)) { msg += '\nBusiness Name is required.'; }
			//if (launch.utils.isBlank($scope.user.phoneNumber)) { msg += '\nPhone Number is required.'; }

			if (launch.utils.isBlank($scope.user.emailAddress)) {
				msg += '\nEmail Address is required.';
			} else if (!launch.utils.isValidEmail($scope.user.emailAddress)) {
				msg += '\nPlease enter a valid Email Address.';
			}

			if (!launch.utils.isBlank(msg)) {
				notificationService.error('Error!', 'Please fix the following problems:\n' + msg);
				return;
			}

			var account = accountService.getNewAccount();

			account.name = $scope.user.businessName;
			account.title = $scope.user.title;
			account.active = true;
			account.email = $scope.user.emailAddress;
			account.phoneNumber = $scope.user.phoneNumber;
			account.userCount = 10;
			account.subscription = self.subscription;

			$scope.isSaving = true;

			accountService.addBeta(account, {
				success: function (r) {
					$scope.isSaving = false;
					document.location = '/user/confirm/' + r.confirmation_code;
				},
				error: self.ajaxHandler.error
			});
		};

		self.init();
	}
]);