launch.module.controller('PromoteSettingsController', [
	'$scope','$location', 'AuthService', 'AccountService', 'NotificationService', function($scope, $location, authService, accountService, notificationService) {
		var self = this;

		self.loggedInUser = null;

		self.init = function () {
			self.loggedInUser = authService.userInfo();

			// TODO: GET A LISTING OF THE PROMOTE CONNECTIONS AND USE THEM TO POPULATE THE CONNECTIONS INSTEAD OF HARD-CODING THEM!!
			$scope.connections = [
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
					id: 1004,
					name: 'Outbrain',
					provider: 'outbrain',
					type: 'promote',
					category: 'amplification',
					created: new Date(),
					updated: new Date(),
				},
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
					id: 1005,
					name: 'Papershare',
					provider: 'papershare',
					type: 'promote',
					category: 'intelligence',
					created: new Date(),
					updated: new Date(),
				},
				{
					id: 1003,
					name: 'Act-On',
					provider: 'act-on',
					type: 'promote',
					category: 'automation',
					created: new Date(),
					updated: new Date(),
				}
			];

			self.formatConnectionCategories();
		};

		self.formatConnectionCategories = function() {
			$scope.categories = [];

			for (var i = 0; i < $scope.connections.length; i++) {
				if ($.grep($scope.categories, function (c) { return c.name === $scope.connections[i].category; }).length > 0) {
					continue;
				}

				$scope.categories.push({
					name: $scope.connections[i].category,
					title: launch.utils.titleCase($scope.connections[i].category),
					connections: $.grep($scope.connections, function (c) { return c.category === $scope.connections[i].category; })
				});
			}

			$scope.categories.sort(function (a, b) {
				if ((!a && !b) || (launch.utils.isBlank(a.name) && launch.utils.isBlank(b.name))) { return 0; }
				if ((!a && b) || (launch.utils.isBlank(a.name) && !launch.utils.isBlank(b.name))) { return 1; }
				if ((a && !b) || (!launch.utils.isBlank(a.name) && launch.utils.isBlank(a.name))) { return -1; }
				if (a.name === b.name) { return 0; }

				if (a.name === 'social') { return -1; }
				if (b.name === 'social') { return 1; }

				if (a.name === 'automation') { return -1; }
				if (b.name === 'automation') { return 1; }

				if (a.name === 'amplification') { return -1; }
				if (b.name === 'amplification') { return 1; }

				if (a.name === 'intelligence') { return -1; }
				if (b.name === 'intelligence') { return 1; }

				return 0;
			});
		};

		$scope.connections = null;
		$scope.categories = null;

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