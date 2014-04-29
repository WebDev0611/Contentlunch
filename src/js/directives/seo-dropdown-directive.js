launch.module.directive('seoDropdown', function ($compile, $location, $templateCache) {
	return {
		restrict: 'A',
		scope: {
			controlModel: '=controlModel'
		},
		templateUrl: '/assets/views/seo-dropdown.html',
		link: function(scope, element, attrs) {
			scope.options = launch.config.SEO_PROVIDERS;
		}
	};
});