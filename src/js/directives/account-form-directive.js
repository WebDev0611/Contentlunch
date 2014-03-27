launch.module.directive('accountForm', function ($modal, AuthService, AccountService, NotificationService) {
	var link = function(scope, element, attrs) {
		var self = this;

		self.loggedInUser = null;
		self.originalSubscription = null;

		self.init = function() {
			self.loggedInUser = AuthService.userInfo();
			scope.subscriptions = AccountService.getSubscriptions();
		};

		self.discardChanges = function(form, keepSelected) {
			if ($.isFunction(scope.refreshMethod)) {
				scope.refreshMethod(form, (keepSelected ? scope.selectedAccount : null));
			}
		};

		scope.forceDirty = false;
		scope.isLoading = false;
		scope.isSaving = false;
		scope.hasError = launch.utils.isPropertyValid;
		scope.isNewAccount = false;
		scope.errorMessage = launch.utils.getPropertyErrorMessage;
		scope.subscriptions = [];

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
								self.discardChanges(form, true);
								instance.dismiss('cancel');
							};
						}
					]
				});

				return;
			}

			self.discardChanges(form, false);
		};

		scope.saveAccount = function(form) {
			if (!scope.selectedAccount || scope.selectedAccount.$resolved === false) {
				return;
			}

			scope.forceDirty = true;
			form.$setDirty();

			var msg = launch.utils.validateAll(scope.selectedAccount);

			if (!launch.utils.isBlank(msg)) {
				NotificationService.error('Error!', 'Please fix the following problems:\n\n' + msg.join('\n'));
				return;
			}

			var method = scope.isNewAccount ? AccountService.add : AccountService.update;

			scope.isSaving = true;

			method(scope.selectedAccount, {
				success: function (r) {
					if (scope.isNewAccount || (scope.selectedAccount.subscription.subscriptionLevel !== self.originalSubscription.subscriptionLevel)) {
						// Now save the subscription along with the new account.
						AccountService.updateAccountSubscription(r.id, scope.selectedAccount.subscription);
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
							scope.selectedAccount.active = false;

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

		scope.changeTier = function () {
			AccountService.getSubscription(parseInt(scope.selectedAccount.subscription.id), {
				success: function(s) {
					if (!!self.originalSubscription && s.subscriptionLevel === parseInt(self.originalSubscription.subscriptionLevel)) {
						scope.selectedAccount.subscription = angular.copy(self.originalSubscription);
					} else {
						scope.selectedAccount.subscription = s;
					}

					scope.changePaymentPeriod();
				}
			});
		};

		scope.changePaymentPeriod = function () {
			if (scope.selectedAccount.yearlyPayment !== true) {
				// Monthly payments require that auto-renew is on.
				scope.selectedAccount.autoRenew = true;
			}
		};

		scope.compareTiers = function() {
			$modal.open({
				templateUrl: '/assets/views/tier-info.html',
				windowClass: 'tier-info-dialog',
				controller: [
					'$scope', '$modalInstance', function (scp, instance) {
						scp.subscriptions = AccountService.getSubscriptions();
						scp.mode = 'compare';
						scp.buttonText = 'Request Update';
						scp.forceDirty = false;
						scp.message = {
							company: null,
							name: self.loggedInUser.formatName(),
							email: self.loggedInUser.email,
							phone: self.loggedInUser.phoneNumber,
							details: null,
							validateProperty: function(property) {
								if (launch.utils.isBlank(property)) {
									return null;
								}

								switch (property.toLowerCase()) {
									case 'company':
										return launch.utils.isBlank(scp.message.company) ? 'Company is required' : null;
									case 'name':
										return launch.utils.isBlank(scp.message.name) ? 'Name is required' : null;
									case 'email':
										if (launch.utils.isBlank(scp.message.email)) {
											return 'Email Address is required.';
										} else if (!launch.utils.isValidEmail(scp.message.email)) {
											return 'Please enter a valid Email Address.';
										}

										return null;
									case 'phone':
										return launch.utils.isBlank(scp.message.phone) ? 'Phone Number is required' : null;
									case 'details':
										return launch.utils.isBlank(scp.message.details) ? 'Details are required' : null;
									default:
										return null;
								}
							}
						};
						scp.hasError = function (property, control) {
							return launch.utils.isPropertyValid(scp.message, property, control, scp.forceDirty);
						};
						scp.errorMessage = function (property, control) {
							return launch.utils.getPropertyErrorMessage(scp.message, property, control);
						};
						scp.cancel = function () {
							if (scp.mode === 'compare') {
								instance.dismiss('cancel');
							} else {
								scp.mode = 'compare';
							}
						};
						scp.toggleMode = function (form) {
							if (scp.mode === 'request') {
								scp.forceDirty = true;
								form.$setDirty();

								var msg = launch.utils.validateAll(scp.message);

								if (!launch.utils.isBlank(msg)) {
									NotificationService.error('Error!', 'Please fix the following problems:\n\n' + msg.join('\n'));
									return;
								}

								NotificationService.info('Warning!!', 'This has not yet been implemeneted!');
							} else {
								scp.mode = 'request';
								scp.buttonText = 'Send';
							}
						};
					}
				]
			});
		};

		scope.$watch('selectedAccount', function () {
			if (!scope.selfEditing && (!scope.selectedAccount || launch.utils.isBlank(scope.selectedAccount.id) || scope.selectedAccount.id <= 0)) {
				scope.isNewAccount = true;
			} else {
				scope.isNewAccount = false;
			}

			if (!!scope.selectedAccount && !self.originalSubscription) {


				var setSubscription = function (acct) {
					if (!!acct.subscription) {
						if ($.isFunction(acct.subscription.validateProperty)) {
							self.originalSubscription = angular.copy(acct.subscription);
						} else if (acct.subscription.$promise) {
							acct.subscription.$promise.then(function(s) {
								setSubscription(acct);
							});
						}
					}
				};

				if (scope.selectedAccount.$resolved === true) {
					setSubscription(scope.selectedAccount);
				} else if (scope.selectedAccount.$promise) {
					scope.selectedAccount.$promise.then(setSubscription);
				}
			}
		});

		self.init();
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