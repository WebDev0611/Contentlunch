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

		$scope.formatContentTypeItem = launch.utils.formatContentTypeItem;
		$scope.formatCampaignItem = launch.utils.formatCampaignItem;
		$scope.formatContentConnectionItem = launch.utils.formatContentConnectionItem;
		$scope.getConnectionProviderIconClass = launch.utils.getConnectionProviderIconClass;
		$scope.formatBuyingStageItem = launch.utils.formatBuyingStageItem;

		$scope.formatUserItem = function (item, element, context) {
			return $scope.getUserImageHtml(item.id, item.text);
		};

		$scope.getUserImageHtml = function (userId, text) {
			var user = $.grep($scope.users, function (u, i) { return u.id === parseInt(userId); });
			var style = (user.length === 1 && !launch.utils.isBlank(user[0].image)) ? ' style="background-image: ' + user[0].imageUrl() + '"' : '';

			if (launch.utils.isBlank(text) && user.length === 1) {
				text = user[0].formatName();
			}

			var imageHtml = '<span class="user-image user-image-small"' + style + '></span>';
			var textHtml = '<span class="user-name">' + (launch.utils.isBlank(text) ? '' : text) + '</span>';

			return imageHtml + ' ' + textHtml;
		};

		$scope.providerIsSupported = function (provider) {
			return launch.utils.providerIsSupportsContentType(provider, $scope.content);
		};

		self.init();
	}
]);