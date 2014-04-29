launch.module.directive('roleForm', function ($modal, AuthService, RoleService, NotificationService) {
	var link = function(scope, element, attrs) {
		var self = this;

		self.loggedInUser = null;

		self.init = function () {
			self.loggedInUser = AuthService.userInfo();

			scope.canEditRole = (self.loggedInUser.hasPrivilege('settings_edit_roles'));
			scope.canCreateRole = self.loggedInUser.hasPrivilege('settings_execute_roles');
		};

		self.discardChanges = function (form) {
			if ($.isFunction(scope.refreshMethod)) {
				scope.refreshMethod(form);
			}
		};

		scope.forceDirty = false;
		scope.isLoading = false;
		scope.isSaving = false;
		scope.hasError = launch.utils.isPropertyValid;
		scope.isNewRole = false;
		scope.canEditRole = false;
		scope.canCreateRole = false;
		scope.errorMessage = launch.utils.getPropertyErrorMessage;

		scope.cancelEdit = function (form) {
			if (form.$dirty) {
				$modal.open({
					templateUrl: 'confirm.html',
					controller: [
						'$scope', '$modalInstance', function (scp, instance) {
							scp.message = 'You have not saved your changes. Are you sure you want to cancel?';
							scp.okButtonText = 'Save Changes';
							scp.cancelButtonText = 'Discard Changes';
							scp.onOk = function () {
								scope.saveRole(form);
								instance.close();
							};
							scp.onCancel = function () {
								self.discardChanges(form);
								instance.dismiss('cancel');
							};
						}
					]
				});

				return;
			}

			self.discardChanges(form);
		};

		scope.saveRole = function (form) {
			if (!scope.selectedRole || scope.selectedRole.$resolved === false) {
				return;
			}

			scope.forceDirty = true;
			form.$setDirty();

			var msg = launch.utils.validateAll(scope.selectedRole);

			if (!launch.utils.isBlank(msg)) {
				NotificationService.error('Error!', 'Please fix the following problems:\n\n' + msg.join('\n'));
				return;
			}

			var method = scope.isNewRole ? RoleService.add : RoleService.update;

			scope.isSaving = true;

			method(scope.selectedRole, {
				success: function (r) {
					scope.isSaving = false;

					var successMsg = 'You have successfully saved the role ' + r.displayName + '!';

					NotificationService.success('Success!', successMsg);

					if ($.isFunction(scope.afterSaveSuccess)) {
						scope.afterSaveSuccess(r, form);
					}
				},
				error: function (r) {
					scope.isSaving = false;

					launch.utils.handleAjaxErrorResponse(r, NotificationService);
				}
			});
		};

		scope.deleteRole = function (form) {
			$modal.open({
				templateUrl: 'confirm.html',
				controller: [
					'$scope', '$modalInstance', function (scp, instance) {
						scp.message = 'Are you sure you want to delete this User Role?';
						scp.okButtonText = 'Delete';
						scp.cancelButtonText = 'Cancel';
						scp.onOk = function () {
							scope.isSaving = true;

							RoleService.delete(scope.selectedRole, {
								success: function (r) {
									scope.isSaving = false;

									var successMsg = 'You have successfully deleted the User Role "' + scope.selectedRole.displayName + '"!';

									NotificationService.success('Success!', successMsg);

									if ($.isFunction(scope.afterSaveSuccess)) {
										scope.afterSaveSuccess(r, form);
									}
								},
								error: function (r) {
									scope.isSaving = false;

									launch.utils.handleAjaxErrorResponse(r, NotificationService);
								}
							});
							instance.close();
						};
						scp.onCancel = function () {
							instance.dismiss('cancel');
						};
					}
				]
			});
		};

		scope.duplicateRole = function (form) {
			if (scope.selectedRole.isGlobalAdmin) {
				NotificationService.error('Error!!', 'You cannot duplicate the Global Admin role.');
				return;
			}

			$modal.open({
				templateUrl: 'duplicate-role.html',
				controller: ['$scope', '$modalInstance', function (scp, instance) {
					scp.newRoleName = 'Copy of ' + scope.selectedRole.displayName;
					scp.onOk = function (name) {
						var role = new launch.Role();

						role.name = name.toLowerCase().replace(/\s/g, '_');
						role.displayName = name;
						role.active = scope.selectedRole.active;
						role.isGlobalAdmin = false;
						role.isBuiltIn = true;
						role.isDeletable = true;
						role.accountId = parseInt(scope.selectedRole.accountId);
						role.modules = scope.selectedRole.modules;
						role.created = new Date(scope.selectedRole.created);
						role.updated = new Date(scope.selectedRole.updated);

						scope.isNewRole = true;
						scope.selectedRole = role;

						scope.saveRole(form);

						instance.close();
					};
					scp.onCancel = function() {
						instance.dismiss('cancel');
					};
				}]
			});
		};

		scope.$watch('selectedRole', function () {
			if (!scope.selectedRole || launch.utils.isBlank(scope.selectedRole.id) || scope.selectedRole.id <= 0) {
				scope.isNewRole = true;
			} else {
				scope.isNewRole = false;
			}
		});

		self.init();
	}

	return {
		link: link,
		scope: {
			selectedRole: '=selectedRole',
			refreshMethod: '=refreshMethod',
			afterSaveSuccess: '=afterSaveSuccess',
			selfEditing: '=selfEditing',
			creatingNew: '=creatingNew'
		},
		templateUrl: '/assets/views/role-form.html'
	};
});