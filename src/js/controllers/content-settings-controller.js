launch.module.controller('ContentSettingsController', [
	'$scope', 'AuthService', 'AccountService', 'UserService', 'ContentSettingsService', 'NotificationService', function ($scope, authService, accountService, userService, contentSettingsService, notificationService) {
		var self = this;

		self.loggedInUser = null;
		self.isDirty = false;

		self.init = function() {
			self.loggedInUser = authService.userInfo();

			self.refreshContentSettings();

			$scope.contentTypes = launch.config.CONTENT_TYPES;

			$scope.$on('$locationChangeStart', function (e, newLocation, oldLocation) {
				if (self.isDirty === true) {
					self.updateContentSettings();
				}
			});
		};

		self.refreshContentSettings = function() {
			$scope.contentSettings = contentSettingsService.get(self.loggedInUser.account.id, {
				success: function(r) {
				},
				error: function(r) {
					launch.utils.handleAjaxErrorResponse(r, notificationService);
				}
			});
		};

		self.updateContentSettings = function() {
			contentSettingsService.update($scope.contentSettings, {
				success: function (r) {
					notificationService.success('Success!!', 'Successfully saved Content Settings!');
				},
				error: function (r) {
					launch.utils.handleAjaxErrorResponse(r, notificationService);
				}
			});
		};

		$scope.contentSettings = null;
		$scope.titlePlaceholder = 'Enter a Title';
		$scope.itemPlaceholder = 'Enter Some Text';
		$scope.textEditorSettings = launch.config.TINY_MCE_SETTINGS;

		$scope.setDirty = function() {
			self.isDirty = true;
		};

		$scope.addNewPersona = function () {
			$scope.contentSettings.addEmptyPerona();
		};

		$scope.deletePersona = function(index) {
			$scope.contentSettings.deletePersona(index);
			self.isDirty = true;
		};

		$scope.formatContentTypeItem = function (item, element, context) {
			return '<span class="' + launch.utils.getContentTypeIconClass(item.id) + '"></span> <span>' + item.text + '</span>';
		};

		self.init();
	}
]);