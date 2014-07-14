launch.module.controller('PromoteController', [
	'$scope', '$location', '$modal', 'AuthService', 'UserService', 'ContentSettingsService', 'ContentService', 'CampaignService', 'NotificationService', function ($scope, $filter, $location, $modal, authService, userService, contentSettingsService, contentService, campaignService, notificationService) {
		var self = this;

		self.loggedInUser = null;

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