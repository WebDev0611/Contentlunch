launch.module.controller('NavigationController', [
		'$scope', '$location', function ($scope, $location) {
			$scope.title = 'This is the Navigation controller';
			$scope.isLoggedIn = !!launch.token;
			$scope.menu = getNavigationItems();

			function getNavigationItems () {
				var items = [];

				if ($scope.isLoggedIn) {
					items.push({ title: 'HOME', url: '/', active: true });
					items.push({ title: 'CONSULT', url: '/consult', active: false });
					items.push({ title: 'CREATE', url: '/create', active: false });
					items.push({ title: 'CALENDAR', url: '/calendar', active: false });
					items.push({ title: 'LAUNCH', url: '/launch', active: false });
					items.push({ title: 'MEASURE', url: '/measure', active: false });
				}

				return items;
			}

			function detectRoute () {
				angular.forEach($scope.menu, function (item) {
					item.active = $location.path().match(new RegExp(item.url)) ? true : false;
				});
			}

			$scope.$on('$routeChangeSuccess', detectRoute);
		}
	])
	.directive('navigationTemplate', function () {
		return {
			templateUrl: '/assets/views/navigation.html'
		};
	});