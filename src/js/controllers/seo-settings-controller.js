launch.module.controller('SeoSettingsController', [
	'$scope', '$location', '$modal', 'AuthService', 'AccountService', 'ConnectionService', 'NotificationService', function ($scope, $location, $modal, authService, accountService, connectionService, notificationService) {
		var self = this;

		self.loggedInUser = null;

		self.init = function () {
			self.loggedInUser = authService.userInfo();

			self.refreshSeoSettings();
		};

		self.refreshSeoSettings = function (onAfterSave) {
			$scope.seoSettings = connectionService.querySeoConnections(self.loggedInUser.account.id, {
				success: function (r) {
					if ($.isFunction(onAfterSave)) {
						onAfterSave();
					}
				},
				error: function (r) {
					launch.utils.handleAjaxErrorResponse(r, notificationService);
				}
			});
		};

		$scope.seoSettings = null;

		self.init();
	}
]);