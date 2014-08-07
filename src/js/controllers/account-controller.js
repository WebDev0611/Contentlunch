launch.module.controller('AccountController', [
	'$scope', '$filter', '$location', 'AuthService', 'AccountService', 'UserService', 'NotificationService', 'SessionService', function ($scope, $filter, $location, authService, accountService, userService, notificationService, sessionService) {
		var self = this;

		self.ajaxHandler = {
			success: function (r) {

			},
			error: function (r) {
				launch.utils.handleAjaxErrorResponse(r, notificationService);
			}
		};

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
			var loggedInUser = authService.fetchCurrentUser({
				success: function (r) {
					sessionService.set(sessionService.USER_KEY, loggedInUser);
					sessionService.set(sessionService.ACCOUNT_KEY, account);
				},
				error: self.ajaxHandler.error
			});
		};

		self.init();
	}
]);