launch.module.controller('AccountController', [
	'$scope', '$filter', '$location', 'AuthService', 'AccountService', 'UserService', 'NotificationService', 'SessionService', function ($scope, $filter, $location, authService, accountService, userService, notificationService, sessionService) {
		var self = this;

		self.accountId = null;
		self.loggedInUser = null;

		self.init = function () {
			$scope.refreshMethod();
		};

		self.loadAccount = function (callback) {
			$scope.isLoading = true;

			$scope.selectedAccount = accountService.get({ id: self.accountId }, {
				success: function (account) {
					$scope.isLoading = false;
					$scope.selectedAccount = account;

					if (!!callback && $.isFunction(callback.success)) {
						callback.success(account);
					}
				},
				error: function (r) {
					$scope.isLoading = false;

					launch.utils.handleAjaxErrorResponse(r, notificationService);
				}
			});
		};

		$scope.isLoading = false;
		$scope.selectedAccount = null;

		$scope.refreshMethod = function() {
			self.loggedInUser = authService.userInfo();

			if (!self.loggedInUser) {
				$location.path('/login');
				return;
			} else if (!$.isArray(self.loggedInUser.accounts) || self.loggedInUser.accounts.length === 0) {
				$location.path('/login');
				return;
			}

			self.accountId = self.loggedInUser.accounts[0].id;
			self.loadAccount();
		};

		$scope.afterSaveSuccess = function (account, form) {
			$scope.selectedAccount = account;

			self.loggedInUser.accounts[0] = account;

			sessionService.set(sessionService.AUTHENTICATED_KEY, self.loggedInUser);
		};

		self.init();
	}
]);