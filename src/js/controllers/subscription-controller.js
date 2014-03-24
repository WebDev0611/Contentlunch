launch.module.controller('SubscriptionController', [
	'$scope', '$location', 'NotificationService', function ($scope, $location, notificationService) {
		var self = this;

		self.init = function () {
			// TODO: GET SUBSCRIPTIONS FROM API!
			var tier1 = new launch.Subscription(1);
			var tier2 = new launch.Subscription(2);
			var tier3 = new launch.Subscription(3);

			$scope.subscriptions = [tier1, tier2, tier3];
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