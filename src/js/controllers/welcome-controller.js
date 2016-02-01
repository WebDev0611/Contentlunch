launch.module.controller('WelcomeController', [
	'$scope', '$location', 'AuthService', 'NotificationService', function ($scope, $location, authService, notificationService) {
		var self = this;

		self.ajaxHandler = {
			success: function (r) {

			},
			error: function (r) {
				launch.utils.handleAjaxErrorResponse(r, notificationService);
			}
		};

		self.init = function () {
		};

		self.init();
	}
]);