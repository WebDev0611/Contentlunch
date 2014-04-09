launch.module.controller('ContentSettingsController', [
	'$scope', 'AuthService', 'AccountService', 'UserService', 'NotificationService', function ($scope, authService, accountService, userService, notificationService) {
		var self = this;

		self.init = function () {
			$scope.refreshContentSettings();
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

		$scope.refreshContentSettings = function () {
			// TODO: LOAD THIS FROM THE API ONCE IT'S IN PLACE!!
			$scope.contentSettings = new launch.ContentSettings();
		};

		self.init();
	}
]);