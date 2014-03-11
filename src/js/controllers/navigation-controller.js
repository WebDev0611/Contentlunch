launch.module.controller('NavigationController', [
		'$scope', '$location', '$compile', 'AuthService', function ($scope, $location, $compile, authService) {
			var self = this;

			self.init = function () {
				$scope.user = authService.userInfo();

				self.getNavigationItems();

				$scope.$on('$routeChangeSuccess', self.detectRoute);
			};

			self.getNavigationItems = function () {
				if (!$scope.user) {
					$location.path('/login');
					return;
				}

				var imageUrl = ($.isFunction($scope.user.imageUrl)) ? $scope.user.imageUrl() : null;
				var isGlobalAdmin = (!!$scope.user.role && $.isFunction($scope.user.role.isGlobalAdmin)) ? $scope.user.role.isGlobalAdmin() : null;
				var mainNavItems = [];
				var adminMenuItems = [];
				var userMenuItems = [];

				if (authService.isLoggedIn() === true) {
					if (!isGlobalAdmin) {
						mainNavItems.push({ title: 'home', url: '/', active: 'active' });
						mainNavItems.push({ title: 'consult', url: '/consult', active: '' });
						mainNavItems.push({ title: 'create', url: '/create', active: '' });
						mainNavItems.push({ title: 'collaborate', url: '/collaborate', active: '' });
						mainNavItems.push({ title: 'calendar', url: '/calendar', active: '' });
						mainNavItems.push({ title: 'launch', url: '/launch', active: '' });
						mainNavItems.push({ title: 'measure', url: '/measure', active: '' });

						adminMenuItems.push({ text: 'Account Settings', cssClass: 'glyphicon-cog', url: '/account' });
						adminMenuItems.push({ text: 'Users', cssClass: 'glyphicon-user', url: '/users' });
						adminMenuItems.push({ text: 'User Roles', cssClass: 'glyphicon-lock', url: '/roles' });
					} else {
						mainNavItems.push({ title: 'accounts', url: '/accounts', active: '' });
						mainNavItems.push({ title: 'subscription', url: '/subscription', active: '' });

						adminMenuItems.push({ text: 'Users', cssClass: 'glyphicon-user', url: '/users' });
					}

					userMenuItems.push({ text: 'My Account', cssClass: 'glyphicon-user', url: '/user', image: imageUrl });
					userMenuItems.push({ text: 'Logout', cssClass: 'glyphicon-log-out', url: '/login', image: null });
				}

				$scope.menu = mainNavItems;
				$scope.adminMenu = adminMenuItems;
				$scope.userMenu = userMenuItems;
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