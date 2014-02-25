launch.module.controller('NavigationController', [
		'$scope', '$location', 'AuthService', function ($scope, $location, authService) {
			$scope.title = 'This is the Navigation controller';
			$scope.menu = getNavigationItems();
			$scope.activeMenu = 'home';
			$scope.showNav = authService.isLoggedIn;

			$scope.navigate = function(item) {
				$location.url(angular.lowercase(item.url));
			};

			$scope.imagePath = function(item) {
				return '/assets/images/' + angular.lowercase(item.title) + '.svg';
			};

			function getNavigationItems() {
				var items = [];

				if (authService.isLoggedIn() === true) {
					items.push({ title: 'HOME', url: '/', active: null });
					items.push({ title: 'CONSULT', url: '/consult', active: null });
					items.push({ title: 'CREATE', url: '/create', active: null });
					items.push({ title: 'COLLABORATE', url: '/collaborate', active: null });
					items.push({ title: 'CALENDAR', url: '/calendar', active: null });
					items.push({ title: 'LAUNCH', url: '/launch', active: null });
					items.push({ title: 'MEASURE', url: '/measure', active: null });
				}

				return items;
			}

			function detectRoute() {
				angular.forEach($scope.menu, function(item) {
					item.active = $location.path().match(new RegExp(item.url)) ? 'active' : '';

					if (!launch.utils.isBlank(item.active)) {
						launch.activeMenu = $scope.activeMenu = angular.lowercase(item.title);
					}
				});
			}

			$scope.$on('$routeChangeSuccess', detectRoute);
		}
	])
	.directive('navigationTemplate', function() {
		return {
			templateUrl: '/assets/views/navigation.html'
		};
	});