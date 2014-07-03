angular.module('launch').controller('GuestCampaignController', [
	'$scope', '$routeParams', '$location', 'AuthService', 'CampaignService', 'UserService', 'NotificationService', function ($scope, $routeParams, $location, authService, campaignService, userService, notificationService) {
		var self = this;

		self.loggedInUser = null;
		self.campaignId = null;

		self.ajaxHandler = {
			success: function (r) {

			},
			error: function (r) {
				launch.utils.handleAjaxErrorResponse(r, notificationService);
			}
		};

		self.init = function () {
			self.loggedInUser = authService.fetchGuestCollaborator({
				success: function (r) {
					self.campaignId = parseInt($routeParams.campaignId);

					$scope.allItems = [];

					var content = $.map(self.loggedInUser.content, function (c) { return { id: 'content-' + c.id, itemId: c.id, title: 'Content: ' + c.title, type: 'Content' }; });
					var campaigns = $.map(self.loggedInUser.campaigns, function (c) { return { id: 'campaigns-' + c.id, itemId: c.id, title: 'Campaign: ' + c.title, type: 'Campaigns' }; });

					$.merge($scope.allItems, content);
					$.merge($scope.allItems, campaigns);

					if ($.isArray(self.loggedInUser.campaigns) && self.loggedInUser.campaigns.length > 0) {
						var item = $.grep(self.loggedInUser.campaigns, function (c) { return c.id === self.campaignId; });

						if (item.length === 1) {
							$scope.selectedItem = item[0];
							$scope.selectedItemId = 'campaign-' + $scope.selectedItem.id;
							$scope.canViewCampaign = true;
							$scope.isLoading = false;

							self.refreshComments();
						}
					}
				},
				error: self.ajaxHandler.error
			});
		};

		self.refreshComments = function () {
			$scope.comments = campaignService.queryComments(self.loggedInUser.accountId, $scope.selectedItem.id, null, self.ajaxHandler);
		};

		$scope.allItems = null;
		$scope.selectedItem = null;
		$scope.selectedItemId = null;
		$scope.comments = null;
		$scope.newComment = null;

		$scope.isLoading = true;
		$scope.canViewCampaign = false;
		$scope.showSelector = false;

		$scope.formatContentTypeItem = launch.utils.formatContentTypeItem;
		$scope.formatCampaignItem = launch.utils.formatCampaignItem;

		$scope.formatUserItem = function (item, element, context) {
			if (!!$scope.selectedItem && !!$scope.selectedItem.author) {
				var imageHtml = '<span class="user-image user-image-small"' + $scope.selectedItem.author.imageUrl() + '></span>';
				var textHtml = '<span class="user-name">' + $scope.selectedItem.author.formatName() + '</span>';

				return imageHtml + ' ' + textHtml;
			}

			return null;
		};

		$scope.formatItem = function (item, element, context) {
			if (launch.utils.startsWith(item.type, 'campaign')) {
				return 'Campaign: ' + item.text;
			}

			return 'Content: ' + item.text;
		};

		$scope.addComment = function (message) {
			launch.utils.insertComment(message, $scope.selectedItem.id, self.loggedInUser, campaignService, notificationService, {
				success: function (r) {
					self.refreshComments();
				},
				error: self.ajaxHandler.error
			});
		};

		$scope.changeItem = function () {
			var selectedItem = $.grep($scope.allItems, function (i) {
				return i.id === $scope.selectedItemId;
			});

			if (selectedItem.length === 1) {
				$location.path('/collaborate/guest/' + selectedItem[0].type.toLowerCase() + '/' + selectedItem[0].itemId);
			}
		};

		self.init();
	}
]);