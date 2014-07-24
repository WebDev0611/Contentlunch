﻿launch.module.controller('ContentConnectionsController', [
	'$scope', '$filter', '$location', '$modal', 'AuthService', 'AccountService', 'UserService', 'NotificationService', 'ConnectionService', function($scope, $filter, $location, $modal, authService, accountService, userService, notificationService, connectionService) {
		var self = this;

		self.loggedInUser = null;

		self.ajaxHandler = {
			success: function (r) {

			},
			error: function (r) {
				launch.utils.handleAjaxErrorResponse(r, notificationService);
			}
		};

		self.loadConnections = function() {
			self.loggedInUser = authService.userInfo();
			$scope.isLoading = true;

			$scope.canEditConnection = self.loggedInUser.hasPrivilege('settings_edit_connections');
			$scope.canCreateConnection = self.loggedInUser.hasPrivilege('settings_execute_connections');

			$scope.connections = connectionService.queryContentConnections(self.loggedInUser.account.id, {
				success: function(r) {
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

		self.saveContentConnection = function(connection, callback) {
			var msg = launch.utils.validateAll(connection);

			if (!launch.utils.isBlank(msg)) {
				notificationService.error('Error!', 'Please fix the following problems:\n\n' + msg.join('\n'));
				return;
			}

			connectionService.updateContentConnection(connection, {
				success: function(r) {
					self.loadConnections();

					if (!!callback && $.isFunction(callback.success)) {
						callback.success(r);
					}
				},
				error: function(r) {
					launch.utils.handleAjaxErrorResponse(r, notificationService);

					if (!!callback && $.isFunction(callback.error)) {
						callback.error(r);
					}
				}
			});
		};

		self.init = function() {
			$scope.providers = connectionService.getProviders('content', self.ajaxHandler);

			self.loadConnections();

			$("[contenteditable]").keypress(function(e) {
				return e.which != 13;
			});
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
			toggleStatus: function(status) {
				this.connectionStatus = status;
				this.applyFilter(true);
			},
			applyFilter: function(reset) {
				$scope.filteredConnections = $filter('filter')($scope.connections, function(connection) {
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

		$scope.toggleActiveStatus = function(connection) {
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
				error: function(r) {
					$scope.selectedConnection = null;
				}
			});
		};

		$scope.selectConnection = function(ev, connection) {
			$scope.selectedConnection = connection;

			$(ev.currentTarget).off('keypress');

			$(ev.currentTarget).keypress(function (e) {
				return e.which != 13;
			});
		};

		$scope.addConnection = function(providerId) {
			window.location = '/api/account/' + self.loggedInUser.account.id + '/connections/create?connection_id=' + providerId;
		};

    $scope.deleteConnection = function (connection) {
      $modal.open({
        templateUrl: 'confirm.html',
        controller: ['$scope', '$modalInstance', function (
          modalScope, instance) {
          modalScope.identifier = connection.identifier + ' - ' + connection.connectionName;
          modalScope.message = 'Are you sure you want to delete the connection? ' + modalScope.identifier;
          modalScope.okButtonText = 'Delete';
          modalScope.cancelButtonText = 'Cancel';
          modalScope.onOk = function () {

            connectionService.deleteContentConnection(
              connection, {
                success: function(r) {
                  self.loadConnections();
                  notificationService.success('Success!', 'Connection deleted: ' + modalScope.identifier);
                  instance.close();
                }
              }
            );

          };
          modalScope.onCancel = function () {
            instance.dismiss('cancel');
          };

        }]
      });
    };

		$scope.icon = function(provider) {
			return launch.utils.getConnectionProviderIconClass(provider.toLowerCase());
		};

		self.init();
	}
]);
