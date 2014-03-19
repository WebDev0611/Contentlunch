launch.module.directive('tierComponents', function () {
	var link = function (scope, element, attrs) {
		scope.imageSource = function (component) {
			if (!!component && !launch.utils.isBlank(component.name)) {
				return '/assets/images/' + component.name + '.svg';
			}

			return null;
		};
	};

	return {
		link: link,
		scope: {
			selectedSubscription: '=selectedSubscription'
		},
		templateUrl: '/assets/views/tier-components.html'
	};
});