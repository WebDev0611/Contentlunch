launch.module.controller('PromoteSettingsController', [
	'$scope','$location', 'AuthService', 'AccountService', 'NotificationService', function($scope, $location, authService, accountService, notificationService) {
		var self = this;

		self.init = function () {
			
		};

		$scope.providerIsConnected = function(provider) {
			// TODO: IMPLEMENT WHETHER THERE IS A CONNECTION TO THE SPECIFIED PROVIDER!!
			return false;
		};

		$scope.connect = function (provider) {
			// TODO: IMPLEMENT CONNECTION TO THE SPECIFIED PROVIDER!!
			console.log('Connecting to ' + provider);
		};

		$scope.disconnect = function (provider) {
			// TODO: IMPLEMENT DISCONNECTION FROM THE SPECIFIED PROVIDER!!
			console.log('Disconnecting from ' + provider);
		};

		self.init();
	}
]);