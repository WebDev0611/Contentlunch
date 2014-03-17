launch.module.controller('ContentConnectionsController', [
	'$scope', '$filter', '$location', 'AuthService', 'AccountService', 'UserService', 'NotificationService', 'SessionService', function ($scope, $filter, $location, authService, accountService, userService, notificationService, sessionService) {
		var self = this;

		self.loadConnections = function () {
			$scope.connections.push({ id: 1, connectionType: 'facebook', url: 'http://www.facebook.com/', name: 'Some Facebook Account' });
			$scope.connections.push({ id: 2, connectionType: 'twitter', url: 'http://www.twitter.com/', name: 'Some Twitter Account' });
			$scope.connections.push({ id: 3, connectionType: 'google', url: 'http://plus.google.com/', name: 'Some Google+ Account' });
			$scope.connections.push({ id: 4, connectionType: 'blogspot', url: 'http://www.blogspot.com/', name: 'Some Blogspot Account' });
		};

		self.init = function () {
			self.loadConnections();
		};

		$scope.connections = [];
		$scope.isSaving = false;

		$scope.addConnection = function () {
			notificationService.info('Warning!', 'THIS IS NOT YET IMPLEMENTED!');
		};

		self.init();
	}
]);