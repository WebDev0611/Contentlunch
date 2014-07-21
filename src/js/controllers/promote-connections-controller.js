launch.module.controller('PromoteConnectionsController', [
	'$scope', '$location', 'AuthService', 'AccountService', 'ConnectionService', 'NotificationService', function ($scope, $location, authService, accountService, connectionService, notificationService) {
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
			self.loggedInUser = authService.userInfo();
			self.loadConnections();
		};

		self.loadConnections = function() {
			$scope.connections = connectionService.queryPromoteConnections(self.loggedInUser.account.id, {
				success: function(r) {
					$scope.providers = connectionService.getProviders('promote', {
						success: function() {
							self.formatConnectionCategories();
						},
						error: self.ajaxHandler.error
					});
				},
				error: self.ajaxHandler.error
			});
		};

		self.formatConnectionCategories = function() {
			$scope.categories = [];

			for (var i = 0; i < $scope.providers.length; i++) {
				if ($.grep($scope.categories, function (c) { return c.name === $scope.providers[i].category; }).length > 0) {
					continue;
				}

				$scope.categories.push({
					name: $scope.providers[i].category,
					title: launch.utils.titleCase($scope.providers[i].category),
					providers: $.grep($scope.providers, function (c) { return c.category === $scope.providers[i].category; })
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

		$scope.providers = null;
		$scope.connections = null;
		$scope.categories = null;

		$scope.providerIsConnected = function (provider) {
			if (!$.isArray($scope.connections) || $scope.connections.length === 0) {
				return false;
			}

			return $.grep($scope.connections, function (c) { return c.provider === provider.name; }).length > 0;
		};

		$scope.connect = function (provider) {
			window.location = '/api/account/' + self.loggedInUser.account.id + '/connections/create?connection_id=' + provider.id;
		};

		$scope.disconnect = function (provider) {
			var connection = $.grep($scope.connections, function (c) { return c.provider === provider.name; });

			if (connection.length === 1) {
				connectionService.deletePromoteConnection(connection[0], self.ajaxHandler);
			}
		};

		self.init();
	}
]);