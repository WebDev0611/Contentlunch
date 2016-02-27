// Displays the subscription form used in the account page.
launch.module.directive('accountSubscription', function(){
	return {
		controller: "AccountSubscriptionController",
		controllerAs: "sctrl",
		restrict: 'E',
		templateUrl: '/assets/views/directives/account-subscription.html'
}});
