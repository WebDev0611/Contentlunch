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
						$scope.contentConnectionIds = $.map($scope.content.accountConnections, function(cc) { return cc.id.toString(); });
					},
					error: function (r) {
						launch.utils.handleAjaxErrorResponse(r, notificationService);
					}
				});
				$scope.isNewContent = false;
			}
		};

		self.updateContentConnection = function() {
			var contentConnectionIds = $.map($scope.contentConnectionIds, function (id) { return parseInt(id); });
			var contentConnections = $.grep($scope.contentConnections, function(cc) { return $.inArray(cc.id, contentConnectionIds) >= 0; });

			$scope.content.accountConnections = contentConnections;
		};

		$scope.content = null;
		$scope.contentTypes = null;
		$scope.contentSettings = null;
		$scope.contentConnections = null;
		$scope.campaigns = null;
		$scope.users = null;
		$scope.buyingStages = null;
		$scope.isNewContent = true;
		$scope.forceDirty = false;
		$scope.contentConnectionIds = null;

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

		$scope.saveContent = function () {
			if (!$scope.content || $scope.content.$resolved === false) {
				return;
			}

			$scope.forceDirty = true;

			var msg = launch.utils.validateAll($scope.content);

			if (!launch.utils.isBlank(msg)) {
				notificationService.error('Error!', 'Please fix the following problems:\n\n' + msg.join('\n'));
				return;
			}

			var method = $scope.isNewContent ? contentService.add : contentService.update;

			$scope.isSaving = true;

			//$scope.content.accountConnections = $.map($scope.content.accountConnections, function(cc) { return JSON.parse(cc); });
			//$scope.content.accountConnections = $.grep($scope.contentConnections, function(cc) { return $.inArray(cc.id, $scope.contentConnectionIds) >= 0; });
			self.updateContentConnection();

			method(self.loggedInUser.account.id, $scope.content, {
				success: function (r) {
					$scope.isSaving = false;

					var successMsg = $scope.isNewContent ? 'Successfully created new ' + $scope.content.contentType.name + '!' : 'Successfully updated ' + $scope.content.contentType.name + '!';

					notificationService.success('Success!', successMsg);

					if ($scope.isNewContent) {
						$location.path('/create/content/edit/' + r.id);
					} else {
						self.refreshContent();
					}
				},
				error: function (r) {
					$scope.isSaving = false;
					launch.utils.handleAjaxErrorResponse(r, notificationService);
				}
			});
		};

		$scope.submitForEditing = function() {
			notificationService.info('WARNING!', 'THIS IS NOT YET IMPLEMENTED!!');
		};

		$scope.updateContentType = function () {
			var contentTypeName = $scope.content.contentType.name;
			var contentType = $.grep($scope.contentTypes, function (ct) { return ct.name === contentTypeName; });

			$scope.content.contentType = contentType[0];
		};

		$scope.updateAuthor = function () {
			var userId = parseInt($scope.content.author.id);
			var user = $.grep($scope.users, function (u) { return u.id === userId; });

			$scope.content.author = user[0];
		};

		$scope.updateCampaign = function () {
			var campaignId = parseInt($scope.content.campaign.id);
			var campaign = $.grep($scope.campaigns, function (u) { return u.id === campaignId; });

			$scope.content.campaign = campaign[0];
		};

		self.init();
	}
]);