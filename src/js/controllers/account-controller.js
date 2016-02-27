launch.module.controller('AccountController', [
	'$scope', '$filter', '$location', 'AuthService',
	'AccountService', 'UserService',
	'NotificationService', 'SessionService',
	'userInfo',
	function ($scope, $filter, $location, authService, accountService, userService, notificationService, sessionService, userInfo) {
		var self = this;

		self.ajaxHandler = {
			success: function (r) {

			},
			error: function (r) {
				$scope.isLoading = false;
				launch.utils.handleAjaxErrorResponse(r, notificationService);
			}
		};

		self.init = function () {
			//$scope.refreshMethod();
		};

		$scope.selectedAccount = userInfo.account;
		$scope.isLoading = false;


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