launch.module.controller('ContentConnectionsController', [
	'$scope', '$filter', '$location', '$modal', 'AuthService', 'AccountService', 'UserService', 'NotificationService', 'ConnectionService', function($scope, $filter, $location, $modal, authService, accountService, userService, notificationService, connectionService) {
		var self = this;

		self.loggedInUser = null;

		self.ajaxHandler = {
			success: function(r) {

			},
			error: function(r) {
				launch.utils.handleAjaxErrorResponse(r, notificationService);
			}
		};

		$scope.initRan = false;

		self.loadConnections = function() {
			self.loggedInUser = authService.userInfo();
			$scope.isLoading = true;

			$scope.canEditConnection = self.loggedInUser.hasPrivilege('settings_edit_connections');
			$scope.canCreateConnection = self.loggedInUser.hasPrivilege('settings_execute_connections');

			$scope.connections = connectionService.queryContentConnections(self.loggedInUser.account.id, {
				success: function(r) {
					$scope.isLoading = false;
					$scope.search.applyFilter();

					var search = $location.search();
					if ( ! $scope.initRan && search.updated) {
						_($scope.connections).forEach(function (connection) {
							if (connection.id == search.updated) {
								notificationService.success("Updated connection: " + connection.identifier + ' - ' + connection.connectionName);
							}
						});
					}
					$scope.initRan = true;
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
			connectionService.checkStatus(self.loggedInUser.account.id, connection.id, {
				success: modalResponse,
				error: modalResponse
			});
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

			$(ev.currentTarget).keypress(function(e) {
				return e.which != 13;
			});
		};

		$scope.addConnection = function(provider) {
			if (provider.provider == 'slideshare') {
				$modal.open({
					template: '<div class="modal-header">Please enter your Slideshare credentials</div><div class="modal-body"><form name="slideshareform" novalidate><div class="form-group"><label for="username">Username</label><input type="text" id="username" ng-model="username" class="form-control" required /></div><div class="form-group"><label for="password">Password</label><input type="password" id="password" ng-model="password" class="form-control" required /></div><div class="buttons clearfix"><button class="btn btn-primary btn-black" ng-click="ok(username, password)" ng-disabled=" ! slideshareform.$valid">Connect</button><button class="btn btn-warning btn-black" ng-click="cancel()">Cancel</button></div></form></div></div>',
					controller: function ($scope, $window, $modalInstance) {
						$scope.username = '';
						$scope.password = '';
						$scope.cancel = function () {
							$modalInstance.dismiss('cancel');
						};
						$scope.ok = function (username, password) {
							var connection = provider;
							connection.username = username;
							connection.password = password;
							connection.connection_id = provider.id;
							connectionService.createConnection(self.loggedInUser.account.id, connection, {
								success: function (response) {
									self.init();
								}
							});
						};
					}
				});
			} else {
				window.location = '/api/account/' + self.loggedInUser.account.id + '/connections/create?connection_id=' + provider.id;
			}
		};

		$scope.deleteConnection = function(connection) {
			$modal.open({
				templateUrl: 'confirm.html',
				controller: [
					'$scope', '$modalInstance', function(
													modalScope, instance) {
						modalScope.identifier = connection.identifier + ' - ' + connection.connectionName;
						modalScope.message = 'Are you sure you want to delete the connection? ' + modalScope.identifier;
						modalScope.okButtonText = 'Delete';
						modalScope.cancelButtonText = 'Cancel';
						modalScope.onOk = function() {

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
						modalScope.onCancel = function() {
							instance.dismiss('cancel');
						};

					}
				]
			});
		};

		$scope.icon = function(provider) {
			return launch.utils.getConnectionProviderIconClass(provider.toLowerCase());
		};

		self.init();
	}
]);
