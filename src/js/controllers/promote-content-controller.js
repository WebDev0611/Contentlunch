launch.module.controller('PromoteContentController', [
	'$scope', '$routeParams', '$filter', '$location', '$modal', 'AuthService', 'AccountService', 'UserService', 'ContentSettingsService', 'ContentService', 'ConnectionService', 'CampaignService', 'TaskService', 'NotificationService', function ($scope, $routeParams, $filter, $location, $modal, authService, accountService, userService, contentSettingsService, contentService, connectionService, campaignService, taskService, notificationService) {
		var self = this;

		self.loggedInUser = null;
		self.contentId = parseInt($routeParams.contentId);

		self.ajaxHandler = {
			success: function (r) {

			},
			error: function (r) {
				launch.utils.handleAjaxErrorResponse(r, notificationService);
			}
		};

		self.init = function () {
			self.loggedInUser = authService.userInfo();

			$scope.content = contentService.get(self.loggedInUser.account.id, self.contentId, {
				success: function(r) {
					self.setPrivileges();
				},
				error: self.ajaxHandler.error
			});
			$scope.contentTypes = contentService.getContentTypes(self.ajaxHandler);
			$scope.users = userService.getForAccount(self.loggedInUser.account.id, null, self.ajaxHandler);
			$scope.contentConnections = connectionService.queryContentConnections(self.loggedInUser.account.id, self.ajaxHandler);
			$scope.campaigns = campaignService.query(self.loggedInUser.account.id, null, self.ajaxHandler);
			$scope.contentSettings = contentSettingsService.get(self.loggedInUser.account.id, {
				success: function (r) {
					if ($.isArray($scope.contentSettings.personaProperties)) {
						$scope.buyingStages = $scope.contentSettings.buyingStages();
					}
				},
				error: self.ajaxHandler.error
			});
		};

		self.setPrivileges = function() {
			$scope.canLaunchContent = ($scope.content.author.id === self.loggedInUser.id) ? self.loggedInUser.hasPrivilege('launch_execute_content_own') : self.loggedInUser.hasPrivilege('launch_execute_content_other');
			$scope.canPromoteContent = ($scope.content.author.id === self.loggedInUser.id) ? self.loggedInUser.hasPrivilege('promote_content_own') : self.loggedInUser.hasPrivilege('promote_content_other');
		};

		$scope.content = null;
		$scope.comments = null;
		$scope.contentTypes = null;
		$scope.contentSettings = null;
		$scope.contentConnections = null;
		$scope.allowedConnections = null;
		$scope.campaigns = null;
		$scope.users = null;
		$scope.selectedConnections = [];

		$scope.canLaunchContent = false;
		$scope.isPromote = true;
		$scope.isReadOnly = true;

		$scope.launchContent = function (connection) {
			console.log('Launching to ' + connection.name);

			contentService.launch(self.loggedInUser.account.id, $scope.content.id, connection.id, {
				success: function (r) {

				},
				error: self.ajaxHandler.error
			});
		};

		$scope.toggleSelectedConnections = function (connection, e) {
			var checkbox = $(e.currentTarget);

			if (checkbox.is(':checked')) {
				if ($.grep($scope.selectedConnections, function (c) { return c.id === connection.id; }).length === 0) {
					$scope.selectedConnections.push(connection);
				}
			} else {
				$scope.selectedConnections = $.grep($scope.selectedConnections, function (c) {
					return c.id !== connection.id;
				});
			}

			e.stopImmediatePropagation();
		};

		$scope.launchSelected = function () {
			if (!$.isArray($scope.selectedConnections) || $scope.selectedConnections.length === 0) {
				notificationService.error('Error!', 'Please select one or more connections to which to launch the content.');
				return;
			}

			$.each($scope.selectedConnections, function (i, c) {
				$scope.launchContent(c);
			});

			$scope.selectedConnections = [];
		};

		$scope.providerIsSupported = function (provider) {
			return launch.utils.providerIsSupportsContentType(provider, $scope.content);
		};

		self.init();
	}
]);