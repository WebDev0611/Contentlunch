﻿launch.module.controller('ContentConnectionsController', [
	'$scope', '$filter', '$location', 'AuthService', 'AccountService', 'UserService', 'NotificationService', 'ConnectionService', function ($scope, $filter, $location, authService, accountService, userService, notificationService, connectionService) {
		var self = this;

		self.loggedInUser = null;

		self.loadConnections = function () {
			self.loggedInUser = authService.userInfo();
			$scope.isLoading = true;

			$scope.connections = connectionService.queryContentConnections(self.loggedInUser.account.id, {
				// TODO: UNCOMMENT THIS WHEN THE CONNECTIONS COME FROM THE API!!
				//success: function (r) {
				//	$scope.isLoading = false;
				//	$scope.search.applyFilter();
				//},
				//error: function(r) {
				//	$scope.isLoading = false;

				//	launch.utils.handleAjaxErrorResponse(r, notificationService);
				//}
			});

			$scope.search.applyFilter();
		};

		self.loadProviders = function() {
			$scope.providers = ['Hubspot', 'LinkedIn', 'Wordpress'];
		};

		self.init = function () {
			self.loadProviders();
			self.loadConnections();
		};

		$scope.providers = [];
		$scope.connections = [];
		$scope.isLoading = false;
		$scope.isSaving = false;

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
			connection.active = !connection.active;

			$scope.search.applyFilter(true);
		};

		$scope.addConnection = function (provider) {
			// TODO: IMPLEMENT THE ABILITY TO ADD CONTENT CONNECTIONS!

			switch (provider.toUpperCase()) {
				case 'LINKEDIN':
					var url = 'https://www.linkedin.com/uas/oauth2/authorization?response_type=code' + 
						'&client_id=' + launch.config.LINKEDIN_API_KEY +
						//'&scope=r_basicprofile r_emailaddress r_contactinfo rw_nus rw_groups rw_company_admin' +
						'&state=' + launch.utils.newGuid() +
						'&redirect_uri=http://local.contentlaunch.com/account/connections';

					window.location = url;
					break;
				default:
					notificationService.info('WARNING!', 'THIS HAS NOT YET BEEN IMPLEMENTED!\n\nNOT READY TO ADD A ' + provider.toUpperCase() + ' CONNECTION YET!!');
			}
		};

		self.init();
	}
]);