launch.module.directive('navigationTemplate', function ($location, $compile, AuthService) {
	var link = function(scope, element, attrs) {
		var self = this;

		self.init = function () {
			self.getLoggedInUser();
			scope.$on('$routeChangeSuccess', self.detectRoute);
		};

		self.getNavigationItems = function() {
			if (!scope.user || !scope.user.$resolved) {
				scope.showNav = false;
				return;
			}

			scope.showNav = true;

			var imageUrl = ($.isFunction(scope.user.imageUrl)) ? scope.user.imageUrl() : null;
			var isGlobalAdmin = (scope.user.role.isGlobalAdmin === true) ? true : false;
			var mainNavItems = [];
			var adminMenuItems = [];
			var userMenuItems = [];

			if (!isGlobalAdmin) {
				mainNavItems = $.map($.grep(scope.user.modules, function(m) {
					return m.isSubscribable;
				}), function(m) {
					return {
						title: m.name,
						url: '/' + m.name,
						active: ''

					};
				});

				mainNavItems.splice(0, 0, { title: 'home', url: '/', active: 'active' });

				if (scope.user.hasModuleAccess('settings')) {
					adminMenuItems.push({ text: 'Account Settings', cssClass: 'glyphicon-cog', url: '/account' });

					if (scope.user.hasPrivilege(['settings_edit_profiles', 'settings_view_profiles', 'settings_execute_users'])) {
						adminMenuItems.push({ text: 'Users', cssClass: 'glyphicon-user', url: '/users' });
					}

					if (scope.user.hasPrivilege(['settings_edit_roles', 'settings_view_roles', 'settings_execute_roles'])) {
						adminMenuItems.push({ text: 'User Roles', cssClass: 'glyphicon-lock', url: '/roles' });
					}
				}
			} else {
				mainNavItems.push({ title: 'accounts', url: '/accounts', active: '' });
				mainNavItems.push({ title: 'subscription', url: '/subscription', active: '' });

				adminMenuItems.push({ text: 'Users', cssClass: 'glyphicon-user', url: '/users' });
			}

			userMenuItems.push({ text: 'My Account', cssClass: 'glyphicon-user', url: '/user', image: imageUrl });
			userMenuItems.push({ text: 'Logout', cssClass: 'glyphicon-log-out', url: '/login', image: null });

			if (scope.user.impersonating) {
				userMenuItems.push({ text: 'Switch Back', cssClass: 'glyphicon-log-out', url: '/impersonate/reset', image: null });
			}

			scope.mainMenu = mainNavItems;
			scope.adminMenu = adminMenuItems;
			scope.userMenu = userMenuItems;

			$.each(scope.mainMenu, function(i, item) {
				if (item.url === '/') {
					item.active = ($location.path() === '/') ? 'active' : '';
				} else {
					item.active = $location.path().match(new RegExp(item.url)) ? 'active' : '';
				}
			});
		};

		self.detectRoute = function () {
			var forceLogout = $location.path() === '/login';

			if (forceLogout) {
				scope.showNav = false;
				scope.user = null;
			} else if (!scope.user) {
				self.getLoggedInUser();
			} else {
				self.getNavigationItems();
			}
		};

		self.getLoggedInUser = function () {
			scope.showNav = AuthService.isLoggedIn();
			self.subscription = null;
			scope.user = AuthService.fetchCurrentUser({
				success: function (user) {
					if ($location.path().indexOf('/user/confirm') === 0) {
						return;
					}

					self.getNavigationItems();
				}
			});
		};

		scope.user = null;
		scope.showNav = false;
		scope.mainMenu = [];
		scope.adminMenu = [];
		scope.userMenu = [];

		scope.navigate = function(url) {
			if (url == '/impersonate/reset') {
				AuthService.impersonateReset();
				return;
			}

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
