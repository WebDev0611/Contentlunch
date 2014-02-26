launch.module.controller('NavigationController', [
		'$scope', '$location', 'AuthService', function ($scope, $location, authService) {
			$scope.title = 'This is the Navigation controller';
			$scope.menu = [];
			$scope.user = { };
			$scope.activeMenu = 'home';
			$scope.showNav = authService.isLoggedIn;

			$scope.init = function() {
				$scope.menu = getNavigationItems();
				$scope.user = authService.userInfo();
			};

			$scope.navigate = function(item) {
				$location.url(angular.lowercase(item.url));
			};

			$scope.imagePath = function(item) {
				return '/assets/images/' + angular.lowercase(item.title) + '.svg';
			};

			$scope.formatUserName = function() {
				var user = $scope.user;

				if (!user) {
					return null;
				}

				if (!launch.utils.isBlank(user.first_name) && !launch.utils.isBlank(user.last_name)) {
					return user.first_name + ' ' + user.last_name;
				}

				return null;
			};

			$scope.formatMenuTitle = function(title) {
				return angular.uppercase(title);
			};

			function getNavigationItems() {
				var items = [];

				if (authService.isLoggedIn() === true) {
					items.push({ title: 'home', url: '/', active: true });
					items.push({ title: 'consult', url: '/consult', active: false });
					items.push({ title: 'create', url: '/create', active: false });
					items.push({ title: 'collaborate', url: '/collaborate', active: false });
					items.push({ title: 'calendar', url: '/calendar', active: false });
					items.push({ title: 'launch', url: '/launch', active: false });
					items.push({ title: 'measure', url: '/measure', active: false });
				}

				return items;
			}

			function detectRoute() {
				angular.forEach($scope.menu, function (item) {
					if (item.url === '/') {
						item.active = ($location.path() === '/') ? 'active' : '';
					} else {
						item.active = $location.path().match(new RegExp(item.url)) ? 'active' : '';
					}

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