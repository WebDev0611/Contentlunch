launch.module.directive('tierTiles', function(ModuleService) {
	var link = function(scope, element, attrs) {
		scope.imageSource = function(component) {
			if (!!component && !launch.utils.isBlank(component.name)) {
				return '/assets/images/' + component.name + '.svg';
			}

			return null;
		};
		scope.modules = ModuleService.modules.query();
		scope.moduleActive = function(module) {
			if (! scope.selectedSubscription) {
				return;
			}
			var active = false;
			angular.forEach(scope.selectedSubscription.components, function(value) {
				if (value.name == module.name) {
					active = true;
				}
			});
			return active ? '' : 'disabled';
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