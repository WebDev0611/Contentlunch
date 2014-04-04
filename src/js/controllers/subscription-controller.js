launch.module.controller('SubscriptionController', [
	'$scope', '$location', 'AccountService', 'NotificationService', function ($scope, $location, accountService, notificationService) {
		var self = this;

		self.init = function () {
			$scope.subscriptions = accountService.getSubscriptions();
		};

		$scope.forceDirty = false;
		$scope.subscriptions = [];
		$scope.isSaving = false;
		$scope.hasError = launch.utils.isPropertyValid;
		$scope.errorMessage = launch.utils.getPropertyErrorMessage;

		$scope.save = function (form) {
			$scope.forceDirty = true;

			var msg = [];

			angular.forEach($scope.subscriptions, function(s, i) {
				var errs = launch.utils.validateAll(s, s.getName() + ':');

				if (!launch.utils.isBlank(errs)) {
					msg.push(errs);
				}
			});

			if (msg.length > 0) {
				notificationService.error('Error!', 'Please fix the following problems:\n\n' + msg.join('\n'));
				return;
			}

			angular.forEach($scope.subscriptions, function(s, i) {
				accountService.saveSubscription(s, {
					success: function(r) {
						notificationService.success('Success!', 'Successfully saved ' + s.getName() + '!');
					},
					error: function(r) {
						launch.utils.handleAjaxErrorResponse(r, notificationService);
					}
				});
			});
		};

		$scope.cancel = function () {
			$scope.subscriptions = accountService.getSubscriptions();
		};

		$scope.toggleActive = function (e, subscription) {
			if (!!subscription) {
				subscription.active = !subscription.active;
			}
		};

		self.init();
	}
]);