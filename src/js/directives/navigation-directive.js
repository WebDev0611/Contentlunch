launch.module.directive('navigationTemplate', function($location, $compile, AuthService) {
	var link = function(scope, element, attrs) {
		var self = this;

		self.init = function() {
			self.getLoggedInUser();

			scope.isSignup = $location.path() === '/signup';

			scope.$on('$routeChangeSuccess', self.detectRoute);
		};

		self.getNavigationItems = function(forceRefresh) {
			if (!scope.user || !scope.user.id) {
				scope.showNav = false;
				return;
			}

			if (!forceRefresh && $.isArray(scope.mainMenu) && scope.mainMenu.length > 0) {
				return;
			}

			scope.showNav = true;

			var imageUrl = ($.isFunction(scope.user.imageUrl)) ? scope.user.imageUrl() : null;
			var isGlobalAdmin = (scope.user.role.isGlobalAdmin === true) ? true : false;
			var mainNavItems = [];
			var adminMenuItems = [];
			var userMenuItems = [];

			if (scope.user.id && !isGlobalAdmin) {
				var navItems = $.map($.grep(scope.user.modules, function(m) {
					return m.isSubscribable;
				}), function(m) {
					return {
						title: m.name,
						url: '/' + m.name,
						active: ''

					};
				});

				// Sort main navigation items
				var sort = ['consult', 'create', 'collaborate', 'calendar', 'promote', 'measure'];
				$.each(sort, function(i, value) {
					$.each(navItems, function(j, navValue) {
						if (value == navValue.title) {
							mainNavItems.push(navValue);
						}
					});
				});

				mainNavItems.splice(0, 0, { title: 'home', url: '/', active: 'active' });

				if (scope.user.hasModuleAccess('settings')) {
					if (scope.user.hasPrivilege(['settings_edit_account_settings', 'settings_edit_content_settings', 'settings_edit_seo_settings'])) {
						adminMenuItems.push({ text: 'Account Settings', cssClass: 'glyphicon-cog', url: '/account' });
					}

					if (scope.user.hasPrivilege(['settings_edit_profiles', 'settings_execute_users'])) {
						adminMenuItems.push({ text: 'Users', cssClass: 'glyphicon-user', url: '/users' });
					}

					if (scope.user.hasPrivilege(['settings_edit_roles', 'settings_execute_roles'])) {
						adminMenuItems.push({ text: 'User Roles', cssClass: 'glyphicon-lock', url: '/roles' });
					}
				}
			} else {
				mainNavItems.push({ title: 'accounts', url: '/accounts', active: '' });
				mainNavItems.push({ title: 'subscription', url: '/subscription', active: '' });
				mainNavItems.push({ title: 'library', url: '/consult/admin-library', active: '' });
				mainNavItems.push({ title: 'forum', url: '/consult/forum', active: '' });

				mainNavItems.push({ title: 'conference', url: '/consult/admin-conference', active: '', image: 'video' });

				mainNavItems.push({ title: 'announce', url: '/announce', active: '', image: 'announcement' });

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

		self.detectRoute = function() {
			var forceLogout = $location.path() === '/login';

			if (forceLogout) {
				scope.showNav = false;
				scope.user = null;
				scope.mainMenu = [];
				scope.adminMenu = [];
				scope.userMenu = [];
				scope.inTrial = false;
			} else {
				self.getLoggedInUser();

				if (!!scope.user && $.isArray(scope.user.modules) && scope.user.modules.length > 0) {
					console.log('CALLING getNavitationItems FROM detectRoute');
					self.getNavigationItems(true);
					console.log('RETURNED ' + scope.mainMenu.length + ' ITEMS');
				}
			}
		};

		self.getLoggedInUser = function() {
			scope.user = AuthService.userInfo();
			scope.account = AuthService.accountInfo();
			self.subscription = null;

			if ($location.path().indexOf('/user/confirm') === 0 || $location.path().indexOf('/login') === 0 ||
				$location.path().indexOf('/signup') === 0 || $location.path().indexOf('/collaborate/guest') === 0) {
				scope.inTrial = false;
				return;
			}

			if (!scope.user || !$.isArray(scope.user.modules) || scope.user.modules.length === 0) {
				scope.user = AuthService.fetchCurrentUser({
					success: function(user) {
						if (launch.utils.isBlank(scope.user.id)) {
							$location.path('/login');
							scope.inTrial = false;
						}

						scope.account = AuthService.accountInfo();
						console.log('CALLING getNavitationItems FROM getLoggedInUser (AFTER API REQUEST)');
						self.getNavigationItems(true);
						console.log('RETURNED ' + scope.mainMenu.length + ' ITEMS');
					}
				});
			} else {
				console.log('CALLING getNavitationItems FROM getLoggedInUser');
				self.getNavigationItems(false);
				console.log('RETURNED ' + scope.mainMenu.length + ' ITEMS');

				if (scope.account) {
					scope.trialDays = scope.account.remainingTrialDays();
					scope.inTrial = scope.account.inTrial();
				}

				if (scope.trialDays == 1) {
					scope.lastTrialDay = true;
				}
			}
		};

		scope.user = null;
		scope.account = null;
		scope.lastTrialDay = false;
		scope.inTrial = false;
		scope.showNav = false;
		scope.isSignup = false;
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

		scope.formatMenuTitle = function(title) {
			return angular.uppercase(title);
		};

		self.init();
	};

	return {
		link: link,
		templateUrl: '/assets/views/directives/navigation.html'
	};
});