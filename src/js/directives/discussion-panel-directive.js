launch.module.directive('discussionPanel', function ($modal, $window, $location) {
	return {
		templateUrl: '/assets/views/discussion-panel.html',
		scope: {
			discussion: '=discussion'
		},
		link: function (scope, element, attrs) {
		}
	};
});
