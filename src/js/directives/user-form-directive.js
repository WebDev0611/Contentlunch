launch.module.directive('userForm', function ($modal, $upload, AuthService, RoleService, UserService, NotificationService) {
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

		scope.roles = [];
		scope.photoFile = null;
		scope.isLoading = false;
		scope.isSaving = false;
		scope.creatingNew = false;
		scope.isUploading = false;
		scope.percentComplete = 0;

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
			if (!scope.selectedUser || scope.selectedUser.$resolved === false) {
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

		scope.uploadPhoto = function (files, form, control) {
			if ($.isArray(files) && files.length !== 1) {
				NotificationService.error('Invalid File!', 'Please make sure to select only one file for upload at a time.');
				$(control).replaceWith(control = $(control).clone(true, true));
				return;
			}

			var file = $.isArray(files) ? files[0] : files;
			var msg = UserService.validatePhotoFile(file);

			if (!launch.utils.isBlank(msg)) {
				NotificationService.error('Invalid File!', msg);
				$(control).replaceWith(control = $(control).clone(true, true));
				return;
			}

			scope.isUploading = true;

			UserService.savePhoto(scope.selectedUser, file, {
				success: function (user) {
					scope.isUploading = false;
					scope.percentComplete = 0;

					NotificationService.success('Success!', 'You have successfully uploaded your photo!');

					scope.selectedUser = user;

					if ($.isFunction(scope.afterSaveSuccess)) {
						scope.afterSaveSuccess(user, form);
					}

					$(control).replaceWith(control = $(control).clone(true, true));
				},
				error: function(r) {
					scope.isUploading = false;
					scope.percentComplete = 0;

					console.log(r);

					launch.utils.handleAjaxErrorResponse(r, NotificationService);

					$(control).replaceWith(control = $(control).clone(true, true));
				},
				progress: function (e) {
					scope.percentComplete = parseInt(100.0 * e.loaded / e.total);
				}
			});
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

		scope.resetPassword = function() {
			$modal.open({
				windowClass: 'round-corner-dialog',
				templateUrl: '/assets/views/reset-password.html',
				controller: [
					'$scope', '$modalInstance', function (scp, instance) {
						scp.currentPassword = null;
						scp.newPassword = null;
						scp.confirmPassword = null;
						scp.isSaving = false;

						scp.passwordError = null;
						scp.newPasswordError = null;
						scp.confirmPasswordError = null;

						scp.changePassword = function (e) {
							if (e.type === 'keypress' && e.charCode !== 13) {
								return;
							}

							scp.currentPassword = this.currentPassword;
							scp.newPassword = this.newPassword;
							scp.confirmPassword = this.confirmPassword;

							// TODO: ADD RULES FOR PASSWORD COMPLEXITY HERE!!!
							scp.passwordError = launch.utils.isBlank(scp.currentPassword) ? 'Current Password is required.' : null;
							scp.newPasswordError = launch.utils.isBlank(scp.newPassword) ? 'New Password is required.' : null;
							scp.confirmPasswordError = launch.utils.isBlank(scp.confirmPassword) ? 'Confirm Password is required.' : ((scp.newPassword !== scp.confirmPassword) ? 'Passwords do not match.' : null);

							if (launch.utils.isBlank(scp.passwordError) && launch.utils.isBlank(scp.newPasswordError) && launch.utils.isBlank(scp.confirmPasswordError)) {
								AuthService.login(scope.selectedUser.userName, scp.currentPassword, false, {
									success: function(r) {
										scope.selectedUser.password = scp.newPassword;
										scope.selectedUser.passwordConfirmation = scp.confirmPassword;

										UserService.update(scope.selectedUser, {
											success: function (res) {
												NotificationService.success('Success!', 'You have successfully changed your password!');
												instance.close();
											},
											error: function(res) {
												launch.utils.handleAjaxErrorResponse(res, NotificationService);
											}
										});
									},
									error: function(r) {
										if (r.status === 401) {
											scp.passwordError = r.data.flash;
										} else {
											launch.utils.handleAjaxErrorResponse(r, NotificationService);
										}
									}
								});
							}
						};
						scp.cancel = function () {
							instance.dismiss('cancel');
						};
					}
				]
			});
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