launch.module.controller('ConceptController', [
	'$scope', '$routeParams', '$filter', '$location', 'AuthService', 'UserService', 'ContentSettingsService', 'ContentService', 'NotificationService', function ($scope, $routeParams, $filter, $location, authService, userService, contentSettingsService, contentService, notificationService) {
		var self = this;

		self.loggedInUser = null;

		self.init = function () {
			self.loggedInUser = authService.userInfo();

			$scope.concept = contentService.getNewConcept(self.loggedInUser);
			$scope.contentTypes = contentService.getContentTypes();
			//TODO: POPULATE CAMPAIGNS FROM API!!
			$scope.campaigns = null;
			$scope.users = userService.getForAccount(self.loggedInUser.account.id);
		}

		$scope.hasError = launch.utils.isPropertyValid;
		$scope.errorMessage = launch.utils.getPropertyErrorMessage;
		$scope.concept = null;
		$scope.contentTypes = null;
		$scope.campaigns = null;
		$scope.users = null;

		$scope.formatContentTypeItem = function (item, element, context) {
			return '<span class="' + launch.utils.getContentTypeIconClass(item.id) + '"></span> <span>' + item.text + '</span>';
		};

		$scope.formatCampaignItem = function (item, element, context) {
			return '<span class="campaign-dot campaign-dot-' + item.id + '"></span> <span>' + item.text + '</span>';
		};

		$scope.formatUserItem = function (item, element, context) {
			var user = $.grep($scope.users, function (u, i) { return u.id === parseInt(item.id); });
			var style = (user.length === 1 && !launch.utils.isBlank(user[0].image)) ? ' style="background-image: ' + user[0].imageUrl() + '"' : '';

			return '<span class="user-image user-image-small"' + style + '></span> <span>' + item.text + '</span>';
		};

		self.init();
	}
]);