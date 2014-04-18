launch.module.controller('AccountController', [
	'$scope', '$filter', '$location', 'AuthService', 'AccountService', 'UserService', 'NotificationService', 'SessionService', function ($scope, $filter, $location, authService, accountService, userService, notificationService, sessionService) {
		var self = this;

		self.init = function () {
			$scope.refreshMethod();
		};

		$scope.selectedAccount = null;

		$scope.refreshMethod = function() {
			var tempAccount = authService.accountInfo();

			// Get the latest version of the account from the database.
			$scope.selectedAccount = accountService.get(tempAccount.id, {
				success: function (account) {
					sessionService.set(sessionService.ACCOUNT_KEY, $scope.selectedAccount);
				}
			});
		};

		$scope.afterSaveSuccess = function (account, form) {
			var loggedInUser = authService.userInfo();

			$scope.selectedAccount = account;

			loggedInUser.accounts[0] = account;

			sessionService.set(sessionService.USER_KEY, loggedInUser);
			sessionService.set(sessionService.ACCOUNT_KEY, account);
		};

		self.init();
	}
]);