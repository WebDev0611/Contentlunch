angular.module('launch').controller('GuestContentController', [
	'$scope', '$routeParams', 'AuthService', 'ContentService', 'NotificationService', function ($scope, $routeParams, authService, contentService, notificationService) {
		var self = this;

		self.loggedInUser = null;
		self.contentId = null;

		self.init = function () {
			self.loggedInUser = authService.userInfo();
			self.refreshContent();
		};

		self.refreshContent = function() {
			self.contentId = parseInt($routeParams.contentId);

			$scope.content = contentService.get(self.loggedInUser.account.id, self.contentId, {
				success: function (r) {
					if ($scope.content.status !== 0 || ! self.validateUser()) {
						$scope.canViewContent = false;
						return;
					}
				},
				error: function (r) {
					launch.utils.handleAjaxErrorResponse(r, notificationService);
				}
			});
		};

		self.validateUser = function() {
			// TODO: VALIDATE THAT THE CURRENT USER IS ALLOWED TO ACCESS THIS CONENT ITEM!
			return true;
		};

		$scope.content = null;
		$scope.canViewContent = true;
	}
]);