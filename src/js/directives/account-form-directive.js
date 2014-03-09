launch.module.directive('accountForm', function ($modal, AccountService, NotificationService) {
	var link = function(scope, element, attrs) {
		var self = this;

		self.forceDirty = false;

		self.init = function() {
		};

		self.discardChanges = function(form) {
			if ($.isFunction(scope.refreshMethod)) {
				scope.refreshMethod(form);
			}
		};

		scope.isLoading = false;
		scope.isSaving = false;
		scope.creatingNew = false;

		scope.cancelEdit = function(form) {
			if (form.$dirty) {
				$modal.open({
					templateUrl: 'confirm-cancel.html',
					controller: [
						'$scope', '$modalInstance', function(scp, instance) {
							scp.save = function() {
								scope.saveAccount(form);
								instance.close();
							};
							scp.cancel = function() {
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
			//if (!scope.selectedAccount || (!scope.selectedAccount.$resolved && scope.selfEditing)) {
			//	return;
			//}

			//self.forceDirty = true;
			//form.$setDirty();

			//var msg = launch.utils.validateAll(scope.selectedAccount);
			//var isNew = launch.utils.isBlank(scope.selectedAccount.id);

			//if (!launch.utils.isBlank(msg)) {
			//	NotificationService.error('Error!', 'Please fix the following problems:\n\n' + msg.join('\n'));
			//	return;
			//}

			//var method = isNew ? AccountService.add : AccountService.update;

			//scope.isSaving = true;

			//method(scope.selectedAccount, {
			//	success: function(r) {
			//		scope.isSaving = false;

			//		var successMsg = 'You have successfully saved ' + (scope.selfEditing ? 'your' : r.title + '\'s') + ' account settings!';

			//		NotificationService.success('Success!', successMsg);

			//		if ($.isFunction(scope.afterSaveSuccess)) {
			//			scope.afterSaveSuccess(r, form);
			//		}
			//	},
			//	error: function(r) {
			//		scope.isSaving = false;

			//		launch.utils.handleAjaxErrorResponse(r, NotificationService);
			//	}
			//});
		};

		scope.deleteAccount = function(form) {
			$modal.open({
				templateUrl: 'confirm-delete.html',
				controller: [
					'$scope', '$modalInstance', function (scp, instance) {
						scp.deleteType = 'account';
						scp.delete = function() {
							//scope.isSaving = true;

							//AccountService.delete(scope.selectedAccount, {
							//	success: function(r) {
							//		scope.isSaving = false;

							//		var successMsg = 'You have successfully deleted ' + r.title + '!';

							//		NotificationService.success('Success!', successMsg);

							//		if ($.isFunction(scope.afterSaveSuccess)) {
							//			scope.afterSaveSuccess(r, form);
							//		}
							//	},
							//	error: function(r) {
							//		scope.isSaving = false;

							//		launch.utils.handleAjaxErrorResponse(r, NotificationService);
							//	}
							//});
							instance.close();
						};
						scp.cancel = function() {
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

		scope.$watch(scope.selectedAccount, function(account) {
			scope.creatingNew = (!!account && !launch.utils.isBlank(account.id));
		});

		self.init();
	};

	return {
		link: link,
		scope: {
			selectedAccount: '=selectedAccount',
			refreshMethod: '=refreshMethod',
			afterSaveSuccess: '=afterSaveSuccess',
			selfEditing: '=selfEditing'
		},
		templateUrl: '/assets/views/account-form.html'
	};
});