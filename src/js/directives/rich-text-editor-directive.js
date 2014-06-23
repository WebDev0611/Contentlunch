launch.module.directive('richTextEditor', [
	'$window', '$compile', '$location', '$templateCache',
	function($window, $compile, $location, $templateCache) {
		var link = function(scope, element, attrs, ngModel) {
			var instance;
			var settings = launch.config.TINY_MCE_SETTINGS;

			if (element.is(':disabled')) {
				settings.readonly = 1;
			}

			// make it so we don't need to specify an ID
			// or anything else to make this RTE work
			var id = _.uniqueId('rte-');
			element.attr('id', id);
			settings.selector = '#' + id;

			settings.setup = function(editor) {
				editor.on('Change', function(event) {
					editor.save();

					scope.$apply(function() {
						ngModel.$setViewValue(element.val());
					});
				});

				editor.on('init', function(event) {
					ngModel.$render();
				});
			};

			scope.$on('$destroy', function() {
				tinymce.remove(id);
			});

			ngModel.$render = function() {
				if (instance || (instance = tinymce.get(id)))
					instance.setContent(ngModel.$viewValue || '');
			};

			tinymce.init(settings);
		};

		return {
			require: 'ngModel',
			link: link
		};
	}
]);