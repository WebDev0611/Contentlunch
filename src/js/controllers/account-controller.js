launch.module.controller('AccountController', [
	'$scope', '$filter', '$location', 'AuthService', 'AccountService', 'UserService', 'NotificationService', 'SessionService', function ($scope, $filter, $location, authService, accountService, userService, notificationService, sessionService) {
		var self = this;

		self.init = function () {
			$scope.refreshMethod();

			$scope.settings.push({ id: 'account', title: 'Account Settings' });
			$scope.settings.push({ id: 'connections', title: 'Content Connections' });
			$scope.settings.push({ id: 'content', title: 'Content Settings' });
			$scope.settings.push({ id: 'seo', title: 'SEO Settings' });

			$scope.selectedSetting = $scope.settings[0];
		};

		$scope.settings = [];
		$scope.selectedSetting = null;

		$scope.selectedAccount = null;

		$scope.refreshMethod = function() {
			$scope.selectedAccount = authService.accountInfo();

			if (!$scope.selectedAccount) {
				$location.path('/login');
				return;
			}
		};

		$scope.afterSaveSuccess = function (account, form) {
			var loggedInUser = authService.userInfo();

			$scope.selectedAccount = account;

			loggedInUser.accounts[0] = account;

			sessionService.set(sessionService.USER_KEY, loggedInUser);
			sessionService.set(sessionService.ACCOUNT_KEY, account);
		};

		$scope.toggleSetting = function(setting) {
			$scope.selectedSetting = setting;
		};

		self.init();
	}
]);