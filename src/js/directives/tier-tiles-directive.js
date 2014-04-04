launch.module.directive('tierTiles', function () {
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
		restrict: 'AE',
		scope: {
			selectedSubscription: '=selectedSubscription',
			tileOnClick: '=tileOnClick'
		},
		templateUrl: '/assets/views/tier-tiles.html'
	};
});