launch.module.controller('ContentConnectionsController', [
	'$scope', '$filter', '$location', 'AuthService', 'AccountService', 'UserService', 'NotificationService', 'SessionService', function ($scope, $filter, $location, authService, accountService, userService, notificationService, sessionService) {
		var self = this;

		self.loadConnections = function () {
			// TODO: LOAD THE CONNECTIONS FROM THE API!!
			var facebook = new launch.ContentConnection();
			var twitter = new launch.ContentConnection();
			var google = new launch.ContentConnection();
			var blogspot = new launch.ContentConnection();
			var linkedin = new launch.ContentConnection();

			facebook.id = 1;
			facebook.name = 'Some Facebook Account';
			facebook.url = 'http://www.facebook.com/';
			facebook.connectionType = 'facebook';
			facebook.created = new Date();
			facebook.updated = new Date();

			twitter.id = 2;
			twitter.name = 'Some Twitter Account';
			twitter.url = 'http://www.twitter.com/';
			twitter.connectionType = 'twitter';
			twitter.created = new Date();
			twitter.updated = new Date();

			google.id = 3;
			google.name = 'Some Google+ Account';
			google.url = 'http://plus.google.com/';
			google.connectionType = 'google-plus';
			google.created = new Date();
			google.updated = new Date();

			blogspot.id = 4;
			blogspot.name = 'Some Blogspot Account';
			blogspot.url = 'http://www.blogspot.com/';
			blogspot.connectionType = 'blogspot';
			blogspot.created = new Date();
			blogspot.updated = new Date();

			linkedin.id = 1;
			linkedin.name = 'Some LinkedIn Account';
			linkedin.url = 'http://www.linkedin.com/';
			linkedin.connectionType = 'linkedin';
			linkedin.created = new Date();
			linkedin.updated = new Date();

			$scope.connections.push(facebook);
			$scope.connections.push(twitter);
			$scope.connections.push(google);
			$scope.connections.push(blogspot);
			$scope.connections.push(linkedin);

			$scope.search.applyFilter(false);
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

		$scope.editConnectionName = function(connection) {
			
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