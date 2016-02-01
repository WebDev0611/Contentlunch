launch.module.controller('PromoteConnectionsController', [
	'$scope', '$location', '$modal', 'AuthService', 'AccountService', 'ConnectionService', 'NotificationService', function ($scope, $location, $modal, authService, accountService, connectionService, notificationService) {
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
			self.refreshConnections();
		};

		self.refreshConnections = function() {
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

		$scope.providerIdentifier = function (provider) {
			if (!$.isArray($scope.connections) || $scope.connections.length === 0) {
				return 'Not Connected';
			}

			var provider = _.find($scope.connections, function (c) {
				return c.provider === provider.provider;
			});
			if (provider) {
				if (provider.url && provider.url != 'n/a') {
					return provider.name + '<br /><a href="' + provider.url + '">' + provider.url + '</a>';
				}
				return provider.name;
			}
            else {
                return 'Not Connected'
            }
		};

		$scope.providerIsConnected = function (provider) {
			if (!$.isArray($scope.connections) || $scope.connections.length === 0) {
				return false;
			}

			return $.grep($scope.connections, function (c) { return c.provider === provider.provider; }).length > 0;
		};

		$scope.connect = function(provider) {
			// For hubspot, the user needs to provide their portal id
			if (provider.provider == 'hubspot') {
				$modal.open({
					template: '<div class="modal-header">Enter Your Hubspot Portal ID</div><div class="modal-body"><form name="hubspotform" novalidate><div class="form-group"><label for="portalid">Please enter the portal id you would like to connect to</label><input type="text" id="portalid" ng-model="portalid" class="form-control" required /></div><div class="buttons clearfix"><button class="btn btn-primary btn-black" ng-click="ok(portalid)" ng-disabled=" ! hubspotform.$valid">Connect</button><button class="btn btn-warning btn-black" ng-click="cancel()">Cancel</button></div></form></div></div>',
					controller: function($scope, $window, $modalInstance) {
						$scope.cancel = function() {
							$modalInstance.dismiss('cancel');
						};
						$scope.ok = function(portalid) {
							window.location = '/api/account/' + self.loggedInUser.account.id + '/connections/create?connection_id=' + provider.id + '&portalid=' + portalid;
						};
					}
				});
			} else if (provider.provider == 'hootsuite') {
				notificationService.info('Coming Soon!', 'Unfortunately, we\'re not quite there yet with integrating Hootsuite. Check back again soon!');
			} else if (provider.provider == 'outbrain') {
				notificationService.info('Coming Soon!', 'Unfortunately, we\'re not quite there yet with integrating Outbrain. Check back again soon!');
			} else {
				window.location = '/api/account/' + self.loggedInUser.account.id + '/connections/create?connection_id=' + provider.id;
			}
		};

		$scope.disconnect = function (provider) {
			var connection = $.grep($scope.connections, function (c) { return c.provider === provider.provider; });

			if (connection.length === 1) {
				connectionService.deletePromoteConnection(connection[0], {
					success: function(r) {
						self.refreshConnections();
					},
					error: self.ajaxHandler.error
				});
			}
		};

		$scope.checkStatus = function (connection) {
			var parentScope = $scope;
			var modalResponse = function (response) {
				parentScope.isLoading = false;
				$modal.open({
					template: '<div class="modal-header"><strong>Connection Status: {{ connection.connectionName}} - {{ connection.name }}</strong></div><div class="modal-body"><strong ng-if="success">Connection Status: Success!</strong><strong ng-if=" ! success">Connection Status: Failed</strong><div ng-bind="failMessage"></div><div ng-repeat="data in response"><label>{{ data.label }}</label><br />{{ data.value }}</div></div><div class="modal-footer"><button class="btn btn-primary" ng-click="close()">Close</button></div>',
					controller: function ($scope, $modalInstance) {
						$scope.connection = connection;
						$scope.response = [];
						$scope.success = response[0];
						if (response[0]) {
							_.each(_.keys(response[1]), function (key) { 
								$scope.response.push({ 
									label: key, 
									value: response[1][key] 
								}); 
							});
						} else {
							$scope.failMessage = response[1];
						}
						//_.keys(response)
						$scope.close = function () {
							$modalInstance.dismiss('cancel');
						};
					}
				});
			};
			parentScope.isLoading = true;
			if (!$.isArray($scope.connections) || $scope.connections.length === 0) {
				return false;
			}

			var provider = _.find($scope.connections, function (c) {
				return c.provider === connection.provider;
			});
			if (provider) {
				connectionService.checkStatus(self.loggedInUser.account.id, provider.id, {
					success: modalResponse,
					error: modalResponse
				});
			}
		};

		self.init();
	}
]);