launch.module.controller('NavigationController', [
		'$scope', '$location', '$compile', 'AuthService', function ($scope, $location, $compile, authService) {
			var self = this;

			self.init = function () {
				$scope.menu = self.getNavigationItems();
				$scope.user = authService.userInfo();

				$scope.adminMenu = [
					{ text: 'Account Settings', cssClass: 'glyphicon-cog', url: '/accounts' },
					{ text: 'Users', cssClass: 'glyphicon-user', url: '/users' },
					{ text: 'User Roles', cssClass: 'glyphicon-lock', url: '/roles' }
				];

				$scope.userMenu = [
					{ text: 'My Account', cssClass: 'glyphicon-user', url: '/user', image: $scope.user.imageUrl() },
					{ text: 'Logout', cssClass: 'glyphicon-log-out', url: '/login', image: null }
				];

				$scope.$on('$routeChangeSuccess', self.detectRoute);
			};

			self.getNavigationItems = function() {
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
			};

			self.detectRoute = function() {
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
			};

			$scope.title = 'This is the Navigation controller';
			$scope.menu = [];
			$scope.user = { };
			$scope.activeMenu = 'home';
			$scope.showNav = authService.isLoggedIn;
			$scope.adminMenu = [];
			$scope.userMenu = [];

			$scope.navigate = function(url) {
				$location.url(angular.lowercase(url));
			};

			$scope.imagePath = function(item) {
				return '/assets/images/' + angular.lowercase(item.title) + '.svg';
			};

			$scope.formatMenuTitle = function(title) {
				return angular.uppercase(title);
			};

			self.init();
		}
	])
	.directive('navigationTemplate', function() {
		return {
			templateUrl: '/assets/views/navigation.html'
		};
	});