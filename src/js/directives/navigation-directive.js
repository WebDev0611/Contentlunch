launch.module.directive('navigationTemplate', function ($location, $compile, AuthService) {
	var link = function(scope, element, attrs) {
		var self = this;

		self.init = function() {
			self.getLoggedInUser();
			scope.$on('$routeChangeSuccess', self.detectRoute);
		};

		self.getNavigationItems = function() {
			if (!scope.user) {
				$location.path('/login');
				return;
			}

			var imageUrl = ($.isFunction(scope.user.imageUrl)) ? scope.user.imageUrl() : null;
			var isGlobalAdmin = (!!scope.user.role && $.isFunction(scope.user.role.isGlobalAdmin)) ? scope.user.role.isGlobalAdmin() : false;
			var mainNavItems = [];
			var adminMenuItems = [];
			var userMenuItems = [];

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

			scope.mainMenu = mainNavItems;
			scope.adminMenu = adminMenuItems;
			scope.userMenu = userMenuItems;
		};

		self.detectRoute = function () {
			var forceLogout = $location.path() === '/login';

			angular.forEach(scope.mainMenu, function(item) {
				if (item.url === '/') {
					item.active = ($location.path() === '/') ? 'active' : '';
				} else {
					item.active = $location.path().match(new RegExp(item.url)) ? 'active' : '';
				}

				if (!launch.utils.isBlank(item.active)) {
					launch.activeMenu = scope.activeMenu = angular.lowercase(item.title);
				}
			});

			if (forceLogout) {
				scope.showNav = false;
				scope.use = null;
			} else if (!scope.user) {
				self.getLoggedInUser();
			} else {
				scope.showNav = true;
				self.getNavigationItems();
			}
		};

		self.getLoggedInUser = function() {
			scope.user = AuthService.fetchCurrentUser({
				success: function(user) {
					scope.showNav = AuthService.isLoggedIn();
					self.getNavigationItems();
				}
			});
		};

		scope.user = null;
		scope.mainMenu = [];
		scope.adminMenu = [];
		scope.userMenu = [];
		scope.activeMenu = 'home';

		scope.navigate = function(url) {
			$location.url(angular.lowercase(url));
		};

		scope.imagePath = function(item) {
			return '/assets/images/' + angular.lowercase(item.title) + '.svg';
		};

		scope.formatMenuTitle = function(title) {
			return angular.uppercase(title);
		};

		self.init();
	};

	return {
		link: link,
		templateUrl: '/assets/views/navigation.html'
	};
});
