launch.module.controller('ContentSettingsController', [
	'$scope', '$location', '$modal', 'AuthService', 'AccountService', 'UserService', 'ContentSettingsService', 'NotificationService', function ($scope, $location, $modal, authService, accountService, userService, contentSettingsService, notificationService) {
		var self = this;

		self.loggedInUser = null;
		self.isDirty = false;

		self.init = function() {
			self.loggedInUser = authService.userInfo();
			self.refreshContentSettings();

			$scope.canEditPersonas = self.loggedInUser.hasPrivilege('settings_edit_personas');

			$scope.contentTypes = launch.config.CONTENT_TYPES;

			$scope.$on('$locationChangeStart', function(e, next, current) {
				if (self.isDirty === true) {
					$scope.confirmCancel(false, function() {
						$location.path(next.replace($location.protocol() + '://' + $location.host(), ''));
					});

					e.preventDefault();
				}
			});
		};

		self.refreshContentSettings = function (onAfterSave) {
			$scope.contentSettings = contentSettingsService.get(self.loggedInUser.account.id, {
				success: function (r) {
					self.isDirty = false;

					if ($.isFunction(onAfterSave)) {
						onAfterSave();
					}
				},
				error: function(r) {
					launch.utils.handleAjaxErrorResponse(r, notificationService);
				}
			});
		};

		$scope.contentSettings = null;
		$scope.isSaving = false;
		$scope.titlePlaceholder = 'Enter a Title';
		$scope.itemPlaceholder = 'Enter Some Text';
		$scope.textEditorSettings = launch.config.TINY_MCE_SETTINGS;
		$scope.canEditPersonas = false;

		$scope.updateContentSettings = function (refresh, onAfterSave) {
			$scope.isSaving = true;

			contentSettingsService.update($scope.contentSettings, {
				success: function (r) {
					$scope.isSaving = false;
					self.isDirty = false;
					notificationService.success('Success!!', 'Successfully saved Content Settings!');

					if (refresh) {
						self.refreshContentSettings();
					}

					if ($.isFunction(onAfterSave)) {
						onAfterSave();
					}
				},
				error: function (r) {
					$scope.isSaving = false;
					launch.utils.handleAjaxErrorResponse(r, notificationService);
				}
			});
		};

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

		$scope.confirmCancel = function (isFromUi, onAfterSave) {
			if (self.isDirty) {
				$modal.open({
					templateUrl: 'confirm.html',
					controller: [
						'$scope', '$modalInstance', function(scp, instance) {
							scp.message = 'You have not saved your changes. Do you you want to discard your changes?';
							scp.okButtonText = isFromUi ? 'Cancel' : 'Save Changes';
							scp.cancelButtonText = 'Discard Changes';
							scp.onOk = function () {
								if (!isFromUi) {
									$scope.updateContentSettings(true, onAfterSave);
								}

								instance.close();
							};
							scp.onCancel = function() {
								self.refreshContentSettings(onAfterSave);
								instance.dismiss('cancel');
							};
						}
					]
				});
			}
		};

		$scope.formatContentTypeItem = function (item, element, context) {
			return '<span class="' + launch.utils.getContentTypeIconClass(item.id) + '"></span> <span>' + item.text + '</span>';
		};

		self.init();
	}
]);