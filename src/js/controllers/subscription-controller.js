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

		$scope.save = function() {
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
			// TODO: WHAT TO DO WHEN WE CANCEL?
			notificationService.info('WARNING!', 'This is not yet implemeneted!');
		};

		self.init();
	}
]);