launch.module.controller('NavigationController', [
		'$scope', '$location', '$compile', 'AuthService', function($scope, $location, $compile, authService) {
			$scope.title = 'This is the Navigation controller';
			$scope.menu = [];
			$scope.user = { };
			$scope.activeMenu = 'home';
			$scope.showNav = authService.isLoggedIn;
			$scope.adminMenu = [];
			$scope.userMenu = [];

			$scope.init = function() {
				$scope.menu = getNavigationItems();
				$scope.user = authService.userInfo();

				$scope.adminMenu = [
					{ text: 'Account Settings', cssClass: 'glyphicon-cog', url: '/accounts' },
					{ text: 'Users', cssClass: 'glyphicon-user', url: '/users' },
					{ text: 'User Roles', cssClass: 'glyphicon-lock', url: '/roles' }
				];

				$scope.userMenu = [
					{ text: 'My Account', cssClass: 'glyphicon-user', url: '/user' },
					{ text: 'Logout', cssClass: 'glyphicon-log-out', url: '/login' }
				];
			};

			$scope.navigate = function(url) {
				$location.url(angular.lowercase(url));
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
					items.push({ title: 'home', url: '/', active: 'active' });
					items.push({ title: 'consult', url: '/consult', active: '' });
					items.push({ title: 'create', url: '/create', active: '' });
					items.push({ title: 'collaborate', url: '/collaborate', active: '' });
					items.push({ title: 'calendar', url: '/calendar', active: '' });
					items.push({ title: 'launch', url: '/launch', active: '' });
					items.push({ title: 'measure', url: '/measure', active: '' });
				}

				return items;
			}

			function detectRoute() {
				angular.forEach($scope.menu, function(item) {
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