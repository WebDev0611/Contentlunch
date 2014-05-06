launch.module.controller('ConceptController', [
	'$scope', '$routeParams', '$filter', '$location', 'AuthService', 'UserService', 'ContentSettingsService', 'ContentService', 'NotificationService', function ($scope, $routeParams, $filter, $location, authService, userService, contentSettingsService, contentService, notificationService) {
		var self = this;

		self.loggedInUser = null;

		self.init = function () {
			self.loggedInUser = authService.userInfo();
		}

		self.init();
	}
]);