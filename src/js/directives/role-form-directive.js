launch.module.directive('roleForm', function ($modal, RoleService, NotificationService) {
	var link = function(scope, element, attrs) {
		var self = this;

		self.forceDirty = false;

		self.discardChanges = function (form) {
			if ($.isFunction(scope.refreshMethod)) {
				scope.refreshMethod(form);
			}
		};

		scope.isLoading = false;
		scope.isSaving = false;
		scope.hasError = launch.utils.isPropertyValid;
		scope.isNewRole = false;
		scope.isBuiltIn = false;
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

			self.forceDirty = true;
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

					var successMsg = 'You have successfully saved ' + (scope.selfEditing ? 'your' : r.title + '\'s') + ' role settings!';

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

									var successMsg = 'You have successfully deleted the User Role "' + scope.selectedRole.name + '"!';

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

		scope.$watch('selectedRole', function () {
			if (!scope.selectedRole || launch.utils.isBlank(scope.selectedRole.id) || scope.selectedRole.id <= 0) {
				scope.isNewRole = true;
			} else {
				scope.isNewRole = false;
			}

			scope.isBuiltIn = (!!scope.selectedRole && $.isFunction(scope.selectedRole.isBuiltIn)) ? scope.selectedRole.isBuiltIn() : false;
		});
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