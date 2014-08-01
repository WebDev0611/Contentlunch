launch.module.controller('MeasureContentItemController', [
	'$scope', '$location', 'AuthService', 'NotificationService', function($scope, $location, authService, notificationService) {
		var self = this;

		self.loggedInUser = null;

		self.ajaxHandler = {
			success: function (r) {

			},
			error: function (r) {
				launch.utils.handleAjaxErrorResponse(r, notificationService);
			}
		};

		self.init = function() {
			self.loggedInUser = authService.userInfo();
		}

		self.init();
	}
]);