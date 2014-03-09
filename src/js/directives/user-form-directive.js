﻿launch.module.directive('userForm', function ($modal, RoleService, UserService, NotificationService) {
	var link = function (scope, element, attrs) {
		var self = this;

		self.forceDirty = false;

		self.init = function() {
			scope.roles = RoleService.query();
		};

		self.discardChanges = function (form) {
			if ($.isFunction(scope.refreshMethod)) {
				scope.refreshMethod(form);
			}
		};

		self.validatePhotoFile = function(file) {
			if (!$.inArray(file.type, scope.photoFileTypes)) {
				NotificationService.error('Invalid File!', 'The file you selected is not supported. You may only upload JPG, PNG, GIF, or BMP images.');
				return false;
			} else if (file.size > 5000000) {
				NotificationService.error('Invalid File!', 'The file you selected is too big. You may only upload images that are 5MB or less.');
				return false;
			}

			return true;
		};

		scope.roles = [];
		scope.photoFile = null;
		scope.isLoading = false;
		scope.isSaving = false;
		scope.creatingNew = false;
		scope.photoFileTypes = ['image/gif', 'image/png', 'image/jpeg', 'image/bmp'];

		scope.cancelEdit = function(form) {
			if (form.$dirty) {
				$modal.open({
					templateUrl: 'confirm-cancel.html',
					controller: [
						'$scope', '$modalInstance', function(scp, instance) {
							scp.save = function () {
								scope.saveUser(form);
								instance.close();
							};
							scp.cancel = function () {
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

		scope.saveUser = function (form) {
			if (!scope.selectedUser || (!scope.selectedUser.$resolved && scope.selfEditing)) {
				return;
			}

			self.forceDirty = true;
			form.$setDirty();

			var msg = launch.utils.validateAll(scope.selectedUser);
			var isNew = launch.utils.isBlank(scope.selectedUser.id);

			if (!launch.utils.isBlank(msg)) {
				NotificationService.error('Error!', 'Please fix the following problems:\n\n' + msg.join('\n'));
				return;
			}

			var method = isNew ? UserService.add : UserService.update;

			scope.isSaving = true;

			method(scope.selectedUser, {
				success: function (r) {
					scope.isSaving = false;

					var successMsg = 'You have successfully saved ' + (scope.selfEditing ? 'your' : r.username + '\'s') + ' user settings!';

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

		scope.deleteUser = function(form) {
			$modal.open({
				templateUrl: 'confirm-delete.html',
				controller: [
					'$scope', '$modalInstance', function (scp, instance) {
						scp.deleteType = 'user';
						scp.delete = function () {
							scope.isSaving = true;

							UserService.delete(scope.selectedUser, {
								success: function(r) {
									scope.isSaving = false;

									var successMsg = 'You have successfully deleted ' + r.username + '!';

									NotificationService.success('Success!', successMsg);

									if ($.isFunction(scope.afterSaveSuccess)) {
										scope.afterSaveSuccess(r, form);
									}
								},
								error: function(r) {
									scope.isSaving = false;

									launch.utils.handleAjaxErrorResponse(r, NotificationService);
								}
							});
							instance.close();
						};
						scp.cancel = function () {
							instance.dismiss('cancel');
						};
					}
				]
			});
		};

		scope.uploadPhoto = function (files) {
			if ($.isArray(files) && files.length === 1) {
				return false;
			}

			if (self.validatePhotoFile(files[0])) {
				UserService.savePhoto(scope.selectedUser, files[0], {
					success: function(r) {
						
					},
					error: function(r) {
						
					}
				});

				return true;
			}

			return false;
		};

		scope.errorMessage = function (property, control) {
			if (!control || !control.$dirty) {
				return false;
			}

			return (!scope.selectedUser || (!scope.selectedUser.$resolved && scope.selfEditing)) ? null : scope.selectedUser.validateProperty(property);
		};

		scope.errorState = function (property, control) {
			if (!control || !scope.selectedUser || (!scope.selectedUser.$resolved && scope.selfEditing)) {
				return false;
			}

			if (self.forceDirty) {
				control.$dirty = true;
			}

			control.$invalid = !launch.utils.isBlank(scope.selectedUser.validateProperty(property));

			return (control.$dirty && control.$invalid);
		};

		scope.getStates = function () {
			if (!!scope.selectedUser && !!scope.selectedUser.country) {
				return launch.utils.getStates(scope.selectedUser.country);
			}

			return [];
		};

		scope.$watch(scope.selectedUser, function (user) {
			scope.creatingNew = (!!user && !launch.utils.isBlank(user.id));
		});

		self.init();
	};

	return {
		link: link,
		scope: {
			selectedUser: '=selectedUser',
			refreshMethod: '=refreshMethod',
			afterSaveSuccess: '=afterSaveSuccess',
			selfEditing: '=selfEditing'
		},
		templateUrl: '/assets/views/user-form.html'
	};
});