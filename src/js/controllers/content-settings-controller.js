launch.module.controller('ContentSettingsController', [
	'$scope', 'AuthService', 'AccountService', 'UserService', 'NotificationService', function ($scope, authService, accountService, userService, notificationService) {
		var self = this;

		self.init = function () {
			$scope.refreshContentSettings();
			$scope.contentTypes = launch.config.CONTENT_TYPES;
		};

		$scope.contentSettings = null;

		$scope.addNewPersonaProperty = function(e) {
			if (e.type === 'keypress' && e.charCode !== 13) {
				return;
			}

			var control = $(e.target);

			$scope.contentSettings.addPersonaProperty(control.val());

			control.val(null);
			control.hide();
		};

		$scope.addNewPersona = function () {
			var properties = [];

			for (var i = 0; i < $scope.contentSettings.personaProperties.length; i++) {
				properties.push({ index: i, value: null });
			}

			$scope.contentSettings.personas.push({
				editing: true,
				properties: properties
			});
		};

		$scope.saveContentSettings = function() {
			// TODO: SAVE TO API AFTER A CHANGE TO THE CONTENT SETTINGS!!
		};

		$scope.editPersonaProperty = function(value) {
			// TODO: SAVE TO API AFTER A CHANGE TO THE PERSONA PROPERTIES!!
		};

		$scope.refreshContentSettings = function () {
			// TODO: LOAD THIS FROM THE API ONCE IT'S IN PLACE!!
			$scope.contentSettings = new launch.ContentSettings();
		};

		self.init();
	}
]);