launch.module.controller('MeasureController', [
	'$scope', '$location', 'AuthService', 'NotificationService', function ($scope, $location, authService, notificationService) {
		var self = this;

		self.loggedInUser = null;

		self.init = function() {
			self.loggedInUser = authService.userInfo();

			$scope.selectedTab = 'overview';
		};

		$scope.selectedTab = null;

		$scope.selectTab = function(tab) {
			$scope.selectedTab = tab;
		};

		self.init();
	}
]);