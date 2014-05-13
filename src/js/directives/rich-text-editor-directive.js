launch.module.directive('richTextEditor', function ($window, $compile, $location, $templateCache) {
	var link = function(scope, element, attrs, ngModel) {
		var id = '#' + element.attr('id');
		var settings = launch.config.TINY_MCE_SETTINGS;
		var updateView = function() {
			ngModel.$setViewValue(element.val());

			if (!scope.$root.$$phase) {
				scope.$apply();
			}
		};

		settings.selector = id;
		settings.setup = function(ed) {
			ed.on('Change', function(e) {
				ed.save();
				updateView();
			});
		};

		$window.setTimeout(function() {
			tinymce.init(settings);

			element.on('$destroy', function() {
				tinymce.remove(id);
			});
		}, 250);
	};

	return {
		require: 'ngModel',
		link: link
	};
});