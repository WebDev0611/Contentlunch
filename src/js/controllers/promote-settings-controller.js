launch.module.controller('PromoteSettingsController', [
	'$scope','$location', 'AuthService', 'AccountService', 'NotificationService', function($scope, $location, authService, accountService, notificationService) {
		var self = this;

		self.loggedInUser = null;

		self.init = function () {
			self.loggedInUser = authService.userInfo();

			// TODO: GET A LISTING OF THE PROMOTE CONNECTIONS AND USE THEM TO POPULATE THE CONNECTIONS
			//			INSTEAD OF HARD-CODING THEM!!
			$scope.connections = [
				{
					id: 1001,
					name: 'Hootsuite',
					provider: 'hootsuite',
					type: 'promote',
					category: 'social',
					created: new Date(),
					updated: new Date(),
				},
				{
					id: 1002,
					name: 'Hubspot',
					provider: 'hubspot',
					type: 'promote',
					category: 'automation',
					created: new Date(),
					updated: new Date(),
				},
				{
					id: 1003,
					name: 'Act-On',
					provider: 'acton',
					type: 'promote',
					category: 'automation',
					created: new Date(),
					updated: new Date(),
				},
				{
					id: 1004,
					name: 'Outbrain',
					provider: 'outbrain',
					type: 'promote',
					category: 'amplification',
					created: new Date(),
					updated: new Date(),
				},
				{
					id: 1005,
					name: 'Papershare',
					provider: 'papershare',
					type: 'promote',
					category: 'intelligence',
					created: new Date(),
					updated: new Date(),
				}
			];
		};

		$scope.connections = null;

		$scope.providerIsConnected = function(provider) {
			// TODO: IMPLEMENT WHETHER THERE IS A CONNECTION TO THE SPECIFIED PROVIDER!!
			return false;
		};

		$scope.connect = function (provider) {
			// TODO: IMPLEMENT CONNECTION TO THE SPECIFIED PROVIDER!!
			console.log('Connecting to ' + provider);
		};

		$scope.disconnect = function (provider) {
			// TODO: IMPLEMENT DISCONNECTION FROM THE SPECIFIED PROVIDER!!
			console.log('Disconnecting from ' + provider);
		};

		self.init();
	}
]);