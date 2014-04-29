launch.module.directive('accountLeftPane', function ($location, AuthService) {
	var link = function(scope, element, attrs) {
		var self = this;

		self.loggedInUser = null;

		self.init = function () {
			self.loggedInUser = AuthService.userInfo();

			// TODO: NEED PRIVILEGES FOR EDITING ACCOUNT SETTINGS!!
			scope.settings.push({ id: 'account', title: 'Account Settings' });

			if (self.loggedInUser.hasPrivilege(['settings_edit_connections', 'settings_view_connections', 'settings_execute_connections'])) {
				scope.settings.push({ id: 'connections', title: 'Content Connections' });
			}

			// TODO: NEED PRIVILEGES FOR EDITING CONTENT SETTINGS!!
			//if (self.loggedInUser.hasPrivilege(['settings_edit_personas', 'settings_view_personas'])) {
				scope.settings.push({ id: 'content', title: 'Content Settings' });
			//}

			// TODO: NEED PRIVILEGES FOR EDITING SEO SETTINGS!!
			//if (self.loggedInUser.hasPrivilege([''])) {
				scope.settings.push({ id: 'seo', title: 'SEO Settings' });
			//}
		};

		scope.settings = [];

		scope.toggleSetting = function (setting) {
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
		templateUrl: '/assets/views/account-left-pane.html'
	};
});