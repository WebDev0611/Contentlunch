launch.module.controller('ConfirmController', [
	'$location', '$route', 'AuthService', function($location, $route, authService) {
		// Do a lookup on the confirmation code
		authService.confirm.save({ code: $route.current.params.code }, function(response) {
			// Success, server logs user in and sets user to confirmed.
			// Enable user to set their password
			console.log(response);
		}, function(response) {
			// Failure, confirmation code wasn't valid.
			// Redirect to login page with message
			console.log('failed confirmation code lookup', response);
		});
	}
]);