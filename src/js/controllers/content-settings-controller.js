launch.module.controller('ContentSettingsController', [
	'$scope', 'AuthService', 'AccountService', 'UserService', 'NotificationService', function ($scope, authService, accountService, userService, notificationService) {
		var self = this;

		self.init = function() {
			$scope.contentSettings = new launch.ContentSettings();
			$scope.personaProperties = (!!$scope.contentSettings) ? $scope.contentSettings.getPersonaProperties() : [];
			$scope.contentTypes = launch.config.CONTENT_TYPES;
		};

		$scope.contentSettings = null;
		$scope.personaProperties = [];

		$scope.addNewPersonaProperty = function(e) {
			if (e.type === 'keypress' && e.charCode !== 13) {
				return;
			}

			var control = $(e.target);

			$scope.contentSettings.addPersonaProperty(control.val());
			$scope.personaProperties = (!!$scope.contentSettings) ? $scope.contentSettings.getPersonaProperties() : [];

			control.val(null);
			control.hide();
		};

		$scope.disableControl = function(disabled, control) {
			
		};

		self.init();
	}
]);