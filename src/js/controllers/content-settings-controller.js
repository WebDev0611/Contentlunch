launch.module.controller('ContentSettingsController', [
	'$scope', 'AuthService', 'AccountService', 'UserService', 'ContentSettingsService', 'NotificationService', function ($scope, authService, accountService, userService, contentSettingsService, notificationService) {
		var self = this;

		self.loggedInUser = null;

		self.init = function () {
			self.loggedInUser = authService.userInfo();

			$scope.contentSettings = contentSettingsService.get(self.loggedInUser.account.id, {
				success: function(r) {
				},
				error: function(r) {
					launch.utils.handleAjaxErrorResponse(r, notificationService);
				}
			});

			$scope.contentTypes = launch.config.CONTENT_TYPES;
		};

		$scope.contentSettings = null;
		$scope.titlePlaceholder = 'Enter a Title';
		$scope.itemPlaceholder = 'Enter Some Text';

		$scope.addNewPersona = function () {
			$scope.contentSettings.addEmptyPerona();
		};

		$scope.saveContentSettings = function() {
			// TODO: SAVE TO API AFTER A CHANGE TO THE CONTENT SETTINGS!!
		};

		$scope.editPersonaProperty = function (value) {
			// TODO: SAVE TO API AFTER A CHANGE TO THE PERSONA PROPERTIES!!
		};

		$scope.editPersonaValue = function (value) {
			// TODO: SAVE TO API AFTER A CHANGE TO THE PERSONA PROPERTIES!!
		};

		$scope.testHtml = function (item, element, context) {
			return '<span class="' + launch.utils.getContentTypeIconClass(item.text) + '"></span> <span>' + item.text + '</span>';
		};

		self.init();
	}
]);