launch.module.directive('accountForm', function ($modal, AccountService, NotificationService) {
	var link = function(scope, element, attrs) {
		var self = this;

		self.forceDirty = false;

		self.discardChanges = function(form) {
			if ($.isFunction(scope.refreshMethod)) {
				scope.refreshMethod(form);
			}
		};

		scope.isLoading = false;
		scope.isSaving = false;

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
								scope.saveAccount(form);
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

		scope.saveAccount = function(form) {
			if (!scope.selectedAccount || scope.selectedAccount.$resolved === false) {
				return;
			}

			self.forceDirty = true;
			form.$setDirty();

			var msg = launch.utils.validateAll(scope.selectedAccount);
			var isNew = launch.utils.isBlank(scope.selectedAccount.id);

			if (!launch.utils.isBlank(msg)) {
				NotificationService.error('Error!', 'Please fix the following problems:\n\n' + msg.join('\n'));
				return;
			}

			var method = isNew ? AccountService.add : AccountService.update;

			scope.isSaving = true;

			method(scope.selectedAccount, {
				success: function (r) {
					if (isNew) {
						// TODO: WE NEED TO MAKE A CALL TO SAVE THE SUBSCRIPTION FOR THE ACCOUNT AS WELL!
					}

					scope.isSaving = false;

					var successMsg = 'You have successfully saved ' + (scope.selfEditing ? 'your' : r.title + '\'s') + ' account settings!';

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
		};

		scope.deleteAccount = function(form) {
			$modal.open({
				templateUrl: 'confirm.html',
				controller: [
					'$scope', '$modalInstance', function (scp, instance) {
						scp.message = 'Are you sure you want to delete this account?';
						scp.okButtonText = 'Delete';
						scp.cancelButtonText = 'Cancel';
						scp.onOk = function () {
							scope.isSaving = true;

							AccountService.delete(scope.selectedAccount, {
								success: function(r) {
									scope.isSaving = false;

									var successMsg = 'You have successfully deleted ' + r.title + '!';

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

		scope.errorMessage = function(property, control) {
			if (!control || !control.$dirty) {
				return false;
			}

			return (!scope.selectedAccount || (!scope.selectedAccount.$resolved && scope.selfEditing)) ? null : scope.selectedAccount.validateProperty(property);
		};

		scope.errorState = function(property, control) {
			if (!control || !scope.selectedAccount || (!scope.selectedAccount.$resolved && scope.selfEditing)) {
				return false;
			}

			if (self.forceDirty) {
				control.$dirty = true;
			}

			control.$invalid = !launch.utils.isBlank(scope.selectedAccount.validateProperty(property));

			return (control.$dirty && control.$invalid);
		};

		scope.getStates = function (forCreditCard) {
			if (forCreditCard) {
				if (!!scope.selectedAccount && !!scope.selectedAccount.country && !!scope.selectedAccount.creditCard) {
					return launch.utils.getStates(scope.selectedAccount.creditCard.country);
				}
			} else {
				if (!!scope.selectedAccount && !!scope.selectedAccount.country) {
					return launch.utils.getStates(scope.selectedAccount.country);
				}
			}

			return [];
		};

		scope.impersonateAccount = function() {
			NotificationService.info('Warning!', 'THIS HAS NOT YET BEEN IMPLEMENTED!');
		};

		scope.resendAccountCreationEmail = function() {
			NotificationService.info('Warning!', 'THIS HAS NOT YET BEEN IMPLEMENTED!');
		};

		scope.renewAccount = function() {
			NotificationService.info('WARNING!', 'This has not yet been implemented!');
		};

		scope.cancelAccount = function() {
			$modal.open({
				templateUrl: 'confirm.html',
				controller: [
					'$scope', '$modalInstance', function (scp, instance) {
						scp.message = 'Are you sure you want to Cancel your account? This will close your account and prevent all access.';
						scp.okButtonText = 'Yes, Cancel';
						scp.cancelButtonText = 'Don\'t Cancel';
						scp.onOk = function () {
							scope.isSaving = true;
							scope.selectedAccount.active = 'inactive';

							AccountService.update(scope.selectedAccount, {
								success: function(r) {
									scope.isSaving = false;
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
	};

	return {
		link: link,
		scope: {
			selectedAccount: '=selectedAccount',
			refreshMethod: '=refreshMethod',
			afterSaveSuccess: '=afterSaveSuccess',
			selfEditing: '=selfEditing',
			creatingNew: '=creatingNew'
		},
		templateUrl: '/assets/views/account-form.html'
	};
});