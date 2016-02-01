launch.module.controller('MeasureContentItemController', [
	'$scope', '$routeParams', '$location', 'AuthService', 'ContentService', 'MeasureService', 'NotificationService', function ($scope, $routeParams, $location, authService, contentService, measureService, notificationService) {
		var self = this;

		self.loggedInUser = null;
		self.contentId = null;

		self.ajaxHandler = {
			success: function (r) {
				$scope.isLoading = false;
			},
			error: function (r) {
				launch.utils.handleAjaxErrorResponse(r, notificationService);
			}
		};

		self.init = function() {
			self.loggedInUser = authService.userInfo();

			$scope.isLoading = true;

			$scope.content = contentService.query(self.loggedInUser.account.id, null, {
				success: function (r) {
					$scope.isLoading = false;

					self.contentId = parseInt($routeParams.contentId);

					$scope.selectedContent = $.grep($scope.content, function (c) { return c.id === self.contentId; });
					$scope.selectedContent = ($scope.selectedContent.length === 1) ? $scope.selectedContent[0] : null;

					if (!!$scope.selectedContent) {
						// TODO: GET CONTENT SCORES!!
					}
				},
				error: self.ajaxHandler.error
			});
		}

		$scope.content = null;
		$scope.selectedContent = null;

		$scope.getContentTypeIconClass = launch.utils.getContentTypeIconClass;

		$scope.changeContent = function (content) {
			if (!content && !launch.utils.isBlank(content)) {
				return;
			}

			$location.path('/measure/content/' + content.id);
		};

		self.init();
	}
]);