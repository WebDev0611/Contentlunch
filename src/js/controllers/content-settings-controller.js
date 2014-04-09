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

		$scope.editPersonaProperty = function(value) {
			// TODO: SAVE TO API AFTER A CHANGE TO THE PERSONA PROPERTIES!
		};

		$scope.refreshContentSettings = function() {
			$scope.contentSettings = new launch.ContentSettings();
		};

		self.init();
	}
]);