launch.module.directive('accountLeftPane', function($location, AuthService) {
	var link = function(scope, element, attrs) {
		var self = this;

		self.loggedInUser = null;

		self.init = function() {
			self.loggedInUser = AuthService.userInfo();

			if (self.loggedInUser.hasPrivilege('settings_edit_account_settings')) {
				scope.settings.push({ id: 'account', title: 'Account Settings' });
			}

			if (self.loggedInUser.hasPrivilege('settings_view_content_settings')) {
				scope.settings.push({ id: 'content', title: 'Content Settings' });
			}

			if (self.loggedInUser.hasPrivilege('settings_view_connections')) {
				scope.settings.push({ id: 'connections', title: 'Content Connections' });
				scope.settings.push({ id: 'promote', title: 'Promote Settings' });
				scope.settings.push({ id: 'seo', title: 'SEO Settings' });
			}
		};

		scope.settings = [];

		scope.toggleSetting = function(setting) {
			if (!setting) {
				return;
			}

			if (setting.id === 'account') {
				$location.path('/account');
			} else if (setting.id === 'connections') {
				$location.path('/account/connections');
			} else if (setting.id === 'content') {
				$location.path('/account/content-settings');
			} else if (setting.id === 'seo') {
				$location.path('/account/seo');
			} else if (setting.id === 'promote') {
				$location.path('/account/promote');
			}
		};

		self.init();

		return self;
	};

	return {
		link: link,
		scope: {
			selectedSetting: '=selectedSetting'
		},
		templateUrl: '/assets/views/directives/account-left-pane.html'
	};
});