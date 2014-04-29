launch.module.directive('userForm', function ($modal, $upload, AuthService, RoleService, UserService, AccountService, NotificationService, SessionService) {
	var link = function (scope, element, attrs) {
		var self = this;

		self.loggedInUser = null;

		self.init = function () {
			self.loggedInUser = AuthService.userInfo();
			scope.roles = RoleService.query(self.loggedInUser.account.id);
		};

		self.discardChanges = function (form) {
			if ($.isFunction(scope.refreshMethod)) {
				scope.refreshMethod(form);
			}
		};

		scope.roles = [];
		scope.forceDirty = false;
		scope.photoFile = null;
		scope.isLoading = false;
		scope.isSaving = false;
		scope.isUploading = false;
		scope.isNewUser = false;
		scope.canEditUser = false;
		scope.percentComplete = 0;
		scope.hasError = function (property, control) { return launch.utils.isPropertyValid(scope.selectedUser, property, control, scope.forceDirty); };
		scope.errorMessage = function (property, control) { return launch.utils.getPropertyErrorMessage(scope.selectedUser, property, control); };

		scope.cancelEdit = function(form) {
			if (form.$dirty) {
				$modal.open({
					templateUrl: 'confirm.html',
					controller: [
						'$scope', '$modalInstance', function(scp, instance) {
							scp.message = 'You have not saved your changes. Are you sure you want to cancel?';
							scp.okButtonText = 'Save Changes';
							scp.cancelButtonText = 'Discard Changes';
							scp.onOk = function () {
								scope.saveUser(form);
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

		scope.saveUser = function (form) {
			if (!scope.selectedUser || scope.selectedUser.$resolved === false) {
				return;
			}

			scope.forceDirty = true;
			form.$setDirty();

			var msg = launch.utils.validateAll(scope.selectedUser);

			if (!launch.utils.isBlank(msg)) {
				NotificationService.error('Error!', 'Please fix the following problems:\n\n' + msg.join('\n'));
				return;
			}

			var method = scope.isNewUser ? UserService.add : UserService.update;
			var callback = {
				success: function (r) {
					scope.isSaving = false;

					var successMsg = scope.isNewUser ? 'You have successfully created ' + r.formatName() + '\'s account.' : 'You have successfully saved ' + (scope.selfEditing ? 'your' : r.formatName() + '\'s') + ' user settings!';

					NotificationService.success('Success!', successMsg);

					if ($.isFunction(scope.afterSaveSuccess)) {
						scope.afterSaveSuccess(r, form);
					}
				},
				error: function (r) {
					scope.isSaving = false;

					launch.utils.handleAjaxErrorResponse(r, NotificationService);
				}
			};

			if (scope.isNewUser) {
				scope.selectedUser.account = self.loggedInUser.account;
				scope.selectedUser.accounts.push(scope.selectedUser.account);
				scope.selectedUser.roles.push(scope.selectedUser.role);
			}

			scope.isSaving = true;

			method(scope.selectedUser, {
				success: function (r) {
					if (scope.selfEditing) {
						SessionService.set(SessionService.USER_KEY, scope.selectedUser);
					}

					if (scope.isNewUser && !!scope.selectedUser.account) {
						AccountService.addUser(scope.selectedUser.account.id, r.id, {
							success: function(rs) {
								callback.success(r);
							},
							error: function(rs) {
								callback.error(rs);
							}
						});
					} else {
						callback.success(r);
					}
				},
				error: function(r) {
					callback.error(r);
				}
			});
		};

		scope.deleteUser = function(form) {
			$modal.open({
				templateUrl: 'confirm.html',
				controller: [
					'$scope', '$modalInstance', function (scp, instance) {
						scp.message = 'Are you sure you want to delete this user?';
						scp.okButtonText = 'Delete';
						scp.cancelButtonText = 'Cancel';
						scp.onOk = function () {
							scope.isSaving = true;

							UserService.delete(scope.selectedUser, {
								success: function(r) {
									scope.isSaving = false;

									var successMsg = 'You have successfully deleted ' + scope.selectedUser.formatName() + '!';

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
						scp.onCancel = function () {
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
				$(control).replaceWith($(control).clone(true, true));
				return;
			}

			scope.isUploading = true;

			UserService.savePhoto(scope.selectedUser, file, {
				success: function (user) {
					scope.isUploading = false;
					scope.percentComplete = 0;

					NotificationService.success('Success!', 'You have successfully uploaded your photo!');

					scope.selectedUser = user;

					if (scope.selfEditing) {
						SessionService.set(SessionService.USER_KEY, scope.selectedUser);
					}

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

							scp.passwordError = launch.utils.isBlank(scp.currentPassword) ? 'Current Password is required.' : null;
							scp.newPasswordError = launch.utils.isBlank(scp.newPassword) ? 'New Password is required.' : launch.utils.validatePassword(scp.newPassword);
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

		scope.$watch('selectedUser', function(user) {
			if (!scope.selectedUser || launch.utils.isBlank(scope.selectedUser.id) || scope.selectedUser.id <= 0) {
				scope.isNewUser = true;
			} else {
				scope.isNewUser = false;
			}

			scope.canEditUser = (self.loggedInUser.hasPrivilege('settings_edit_profiles') || scope.selfEditing);
		});

		self.init();
	};

	return {
		link: link,
		scope: {
			selectedUser: '=selectedUser',
			refreshMethod: '=refreshMethod',
			afterSaveSuccess: '=afterSaveSuccess',
			selfEditing: '=selfEditing',
			creatingNew: '=creatingNew'
		},
		templateUrl: '/assets/views/user-form.html'
	};
});