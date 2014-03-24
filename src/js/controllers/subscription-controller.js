launch.module.controller('SubscriptionController', [
	'$scope', '$location', 'AccountService', 'NotificationService', function ($scope, $location, accountService, notificationService) {
		var self = this;

		self.init = function () {
			$scope.subscriptions = accountService.getSubscriptions();
		};

		$scope.subscriptions = [];
		$scope.isSaving = false;
		$scope.hasError = launch.utils.isPropertyValid;
		$scope.errorMessage = launch.utils.getPropertyErrorMessage;

		$scope.save = function () {
			// TODO: SAVE SUBSCRIPTIONS TO API!
			notificationService.info('WARNING!', 'This is not yet implemeneted!');
		};

		$scope.cancel = function () {
			// TODO: WHAT TO DO WHEN WE CANCEL?
			notificationService.info('WARNING!', 'This is not yet implemeneted!');
		};

		$scope.formatPricePerMonth = function (subscription) {
			if (!isNaN(subscription.pricePerMonth)) {
				subscription.pricePerMonth = parseFloat(subscription.pricePerMonth).toFixed(2);
			}
		};

		self.init();
	}
]);