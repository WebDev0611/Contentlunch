launch.module.controller('ContentConnectionsController', [
	'$scope', '$filter', '$location', 'AuthService', 'AccountService', 'UserService', 'NotificationService', 'ConnectionService', function ($scope, $filter, $location, authService, accountService, userService, notificationService, connectionService) {
		var self = this;

		self.loggedInUser = null;

		self.loadConnections = function () {
			self.loggedInUser = authService.userInfo();
			$scope.isLoading = true;

			$scope.canEditConnection = self.loggedInUser.hasPrivilege('settings_edit_connections');
			$scope.canCreateConnection = self.loggedInUser.hasPrivilege('settings_execute_connections');

			$scope.connections = connectionService.queryContentConnections(self.loggedInUser.account.id, {
				success: function (r) {
					$scope.isLoading = false;
					$scope.search.applyFilter();
				},
				error: function(r) {
					$scope.isLoading = false;

					launch.utils.handleAjaxErrorResponse(r, notificationService);
				}
			});

			$scope.search.applyFilter();
		};

		self.loadProviders = function() {
			$scope.providers = launch.config.CONNECTION_PROVIDERS;
		};

		self.saveContentConnection = function (connection, callback) {
			var msg = launch.utils.validateAll(connection);

			if (!launch.utils.isBlank(msg)) {
				notificationService.error('Error!', 'Please fix the following problems:\n\n' + msg.join('\n'));
				return;
			}

			connectionService.updateContentConnection(connection, {
				success: function (r) {
					self.loadConnections();

					if (!!callback && $.isFunction(callback.success)) {
						callback.success(r);
					}
				},
				error: function (r) {
					launch.utils.handleAjaxErrorResponse(r, notificationService);

					if (!!callback && $.isFunction(callback.error)) {
						callback.error(r);
					}
				}
			});
		};

		self.init = function () {
			self.loadProviders();
			self.loadConnections();
		};

		$scope.providers = [];
		$scope.connections = [];
		$scope.isLoading = false;
		$scope.isSaving = false;
		$scope.canEditConnection = false;
		$scope.canCreateConnection = false;
		$scope.selectedConnection = null;

		$scope.search = {
			searchTerm: null,
			searchTermMinLength: 1,
			connectionStatus: 'all',
			toggleStatus: function (status) {
				this.connectionStatus = status;
				this.applyFilter(true);
			},
			applyFilter: function (reset) {
				$scope.filteredConnections = $filter('filter')($scope.connections, function (connection) {
					if ($scope.search.connectionStatus === 'all' || ($scope.search.connectionStatus === 'active' && connection.active) || ($scope.search.connectionStatus === 'inactive' && !connection.active)) {
						if (!launch.utils.isBlank($scope.search.searchTerm) && $scope.search.searchTerm.length >= $scope.search.searchTermMinLength) {
							return (launch.utils.isBlank($scope.search.searchTerm) ? true : connection.matchSearchTerm($scope.search.searchTerm));
						} else {
							return true;
						}
					} else {
						return false;
					}
				});
			}
		};

		$scope.toggleActiveStatus = function (connection) {
			if (!$scope.canEditConnection) {
				return;
			}

			connection.active = !connection.active;

			self.saveContentConnection(connection);

			$scope.search.applyFilter(true);
		};

		$scope.editConnectionName = function(newName) {
			if (!$scope.selectedConnection) {
				return;
			}

			self.saveContentConnection($scope.selectedConnection, {
				success: function(r) {
					$scope.selectedConnection = null;
				},
				error: function (r) {
					$scope.selectedConnection = null;
				}
			});
		};

		$scope.selectConnection = function(connection) {
			$scope.selectedConnection = connection;
		};

		$scope.addConnection = function(provider) {
			var url = null;

			// TODO: IMPLEMENT THE ABILITY TO ADD CONTENT CONNECTIONS!
			switch (provider.toUpperCase()) {
				case 'LINKEDIN':
					var url = 'https://www.linkedin.com/uas/oauth2/authorization?response_type=code' +
						'&client_id=' + launch.config.LINKEDIN_API_KEY +
						//'&scope=r_basicprofile r_emailaddress r_contactinfo rw_nus rw_groups rw_company_admin' +
						'&state=' + launch.utils.newGuid() +
						'&redirect_uri=http://local.contentlaunch.com/account/connections';
					break;
				case 'HUBSPOT':
					var url = 'https://app.hubspot.com/auth/authenticate/?client_id=' + launch.config.HUBSPOT_API_KEY +
						'&portalId=' + '175282' + // TODO: HOW DO WE USE THIS PORTAL ID???
						'&redirect_uri=http://local.contentlaunch.com/account/connections';
					break;
			}

			if (!launch.utils.isBlank(url)) {
				window.location = url;
			} else {
				notificationService.info('WARNING!', 'THIS HAS NOT YET BEEN IMPLEMENTED!\n\nNOT READY TO ADD A ' + provider.toUpperCase() + ' CONNECTION YET!!');
			}
		};

		$scope.icon = function(provider) {
			return launch.utils.getConnectionProviderIconClass(provider.toLowerCase());
		};

		self.init();
	}
]);