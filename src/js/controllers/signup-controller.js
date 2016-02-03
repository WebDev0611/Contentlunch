launch.module.controller('SignupController', [
	'$scope',
	'$modal',
	'$window',
	'$filter',
	'$location',
	'AuthService',
	'AccountService',
	'UserService',
	'NotificationService',
	'SessionService',
	'AnalyticsService',
	function ($scope, $modal, $window, $filter, $location, authService,
			  accountService, userService, notificationService, sessionService, analyticsService) {
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
				emailAddress: null,
				password: null,
				accountType: "single"
			};

			//self.subscription = accountService.getSubscriptions({
			//	success: function(r) {
			//		if (self.subscription.length > 0) {
			//			self.subscription = $.grep(self.subscription, function (s) { return s.subscriptionLevel === 3; });
            //
			//			if (self.subscription.length === 1) {
			//				self.subscription = self.subscription[0];
			//			}
			//		}
			//	},
			//	error: self.ajaxHandler.error
			//});
		};

		$scope.user = null;
		$scope.isSaving = false;

		self.fireConversion = function() {
			analyticsService.trackConversion({
			  google_conversion_id: 1027851638,
			  google_conversion_label: 'kFiJCIGnvVcQ9oqP6gM',
			  google_conversion_language: "en",
			  google_conversion_format: "2",
			  google_conversion_color: "ffffff",
			  google_remarketing_only: false
			});
		};

		$scope.openTerms = function() {
			$modal.open({
				templateUrl: '/assets/views/terms.html',
				windowClass: 'tier-info-dialog',
				controller: [
					'$scope', '$modalInstance', function(scp, instance) {
						
						scp.close = function() {
							instance.dismiss('cancel');
						};
					}
				]
			});
		};

		$scope.signUp = function() {
			var msg = '';

			if (launch.utils.isBlank($scope.user.firstName)) { msg += '\nFirst Name is required.'; }
			if (launch.utils.isBlank($scope.user.lastName)) { msg += '\nLast Name is required.'; }
			//if (launch.utils.isBlank($scope.user.title)) { msg += '\nTitle is required.'; }
			if (launch.utils.isBlank($scope.user.businessName)) { msg += '\nBusiness Name is required.'; }
			if (launch.utils.isBlank($scope.user.phoneNumber)) { msg += '\nPhone Number is required.'; }
			if (launch.utils.isBlank($scope.user.password)) { msg += '\nPassword is required.'; }

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
			account.firstName = $scope.user.firstName;
			account.lastName = $scope.user.lastName;
			account.title = $scope.user.title;
			account.active = true;
			account.email = $scope.user.emailAddress;
			account.phoneNumber = $scope.user.phoneNumber;
			account.userCount = 1;
			//account.subscription = self.subscription;
			account.password = $scope.user.password;
			account.accountType = $scope.user.accountType;


			$scope.isSaving = true;

			accountService.register(account, {
				success: function (r) {
					self.fireConversion();
					$scope.isSaving = false;

					p = authService.fetchCurrentUser();
					p.$promise.then(function(user) {
						// We should now have an account and be logged in
						if(account.accountType == 'agency') {
							$location.path("/agency");
						} else {
							$location.path( "/" );
						}
					});


				},
				error: self.ajaxHandler.error
			});
		};

		self.init();
	}
]);