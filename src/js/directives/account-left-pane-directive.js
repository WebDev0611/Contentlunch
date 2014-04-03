launch.module.directive('accountLeftPane', function ($location) {
	var link = function(scope, element, attrs) {
		var self = this;

		self.init = function () {
			scope.settings.push({ id: 'account', title: 'Account Settings' });
			scope.settings.push({ id: 'connections', title: 'Content Connections' });
			scope.settings.push({ id: 'content', title: 'Content Settings' });
			scope.settings.push({ id: 'seo', title: 'SEO Settings' });
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