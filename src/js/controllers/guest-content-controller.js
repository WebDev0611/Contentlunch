angular.module('launch').controller('GuestContentController', [
	'$scope', '$routeParams', 'AuthService', 'ContentService', 'UserService', 'CampaignService', 'NotificationService', function ($scope, $routeParams, authService, contentService, userService, campaignService, notificationService) {
		var self = this;

		self.loggedInUser = null;
		self.contentId = null;

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
					self.contentId = parseInt($routeParams.contentId);

					if ($.isArray(self.loggedInUser.content) && self.loggedInUser.content.length > 0) {
						var item = $.grep(self.loggedInUser.content, function (c) { return c.id === self.contentId; });

						if (item.length === 1) {
							$scope.selectedItem = item[0];
							$scope.canViewContent = true;
							$scope.isLoading = false;
						}
					}
				},
				error: self.ajaxHandler.error
			});
		};

		$scope.allItems = null;
		$scope.selectedItem = null;
		$scope.comments = null;
		$scope.newComment = null;

		$scope.isLoading = true;
		$scope.canViewContent = false;
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

		$scope.formatItem = function(item, element, context) {
			if (launch.utils.startsWith(item.type, 'campaign')) {
				return 'Campaign: ' + item.text;
			}

			return 'Content: ' + item.text;
		};

		$scope.addComment = function () { };

		self.init();
	}
]);