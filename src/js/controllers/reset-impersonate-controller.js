launch.module.controller('ResetImpersonateController', [
	'$scope', '$location', 'AuthService', 'AccountService', 'NotificationService', 'SessionService', function ($scope, $location, authService, accountService, notificationService, sessionService) {
		var self = this;

		self.ajaxHandler = {
			success: function (r) {

			},
			error: function (r) {
				launch.utils.handleAjaxErrorResponse(r, notificationService);
			}
		};

		self.init = function () {
			authService.impersonateReset();
		}

		self.init();
	}
]);