launch.module.controller('ContentController', [
	'$scope', '$routeParams', '$filter', '$location', '$modal', 'AuthService', 'UserService', 'ContentSettingsService', 'ContentService', 'ConnectionService', 'CampaignService', 'NotificationService', function ($scope, $routeParams, $filter, $location, $modal, authService, userService, contentSettingsService, contentService, connectionService, campaignService, notificationService) {
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
			self.refreshContent();

			$scope.contentConnections = connectionService.queryContentConnections(self.loggedInUser.account.id, self.ajaxHandler);
			$scope.contentTypes = contentService.getContentTypes(self.ajaxHandler);
			$scope.users = userService.getForAccount(self.loggedInUser.account.id, self.ajaxHandler);
			$scope.campaigns = campaignService.query(self.loggedInUser.account.id, self.ajaxHandler);
			$scope.contentSettings = contentSettingsService.get(self.loggedInUser.account.id, {
				success: function (r) {
					$scope.buyingStages = $scope.contentSettings.buyingStages();
				},
				error: self.ajaxHandler.error
			});
		}

		self.refreshContent = function () {
			var contentId = parseInt($routeParams.contentId);

			if (isNaN(contentId)) {
				$scope.content = contentService.getNewContent(self.loggedInUser);
				$scope.isNewContent = true;
			} else {
				$scope.content = contentService.get(self.loggedInUser.account.id, contentId, {
					success: function (r) {

					},
					error: function (r) {
						launch.utils.handleAjaxErrorResponse(r, notificationService);
					}
				});
				$scope.isNewContent = false;
			}
		};

		$scope.content = null;
		$scope.contentTypes = null;
		$scope.contentSettings = null;
		$scope.contentConnections = null;
		$scope.campaigns = null;
		$scope.users = null;
		$scope.buyingStages = null;
		$scope.isNewContent = true;

		$scope.formatContentTypeItem = launch.utils.formatContentTypeItem;
		$scope.formatCampaignItem = launch.utils.formatCampaignItem;
		$scope.formatContentConnectionItem = launch.utils.formatContentConnectionItem;
		$scope.formatBuyingStageItem = launch.utils.formatBuyingStageItem;

		$scope.formatUserItem = function (item, element, context) {
			var user = $.grep($scope.users, function (u, i) { return u.id === parseInt(item.id); });
			var style = (user.length === 1 && !launch.utils.isBlank(user[0].image)) ? ' style="background-image: ' + user[0].imageUrl() + '"' : '';

			return '<span class="user-image user-image-small"' + style + '></span> <span>' + item.text + '</span>';
		};

		$scope.showPublishingGuidelines = function() {
			$modal.open({
				templateUrl: 'publishing-guidelines.html',
				controller: [
					'$scope', '$modalInstance', function (scope, instance) {
						scope.publishingGuidelines = $scope.contentSettings.publishingGuidelines;
						scope.ok = function() {
							instance.dismiss('cancel');
						};
					}
				]
			});
		};

		$scope.analyzeContent = function() {
			notificationService.info('WARNING!', 'THIS IS NOT YET IMPLEMENTED!!');
		};

		$scope.saveContent = function() {
			notificationService.info('WARNING!', 'THIS IS NOT YET IMPLEMENTED!!');
		};

		$scope.submitForEditing = function() {
			notificationService.info('WARNING!', 'THIS IS NOT YET IMPLEMENTED!!');
		};

		self.init();
	}
]);