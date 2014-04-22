launch.module.directive('truncateText', function() {
	var link = function(scope, element, attrs) {
		var options = {
			watch: true
		};

		if (!launch.utils.isBlank(element.css('max-height'))) {
			options.height = element.css('max-height');
		}

		if (!launch.utils.isBlank(scope.expandLinkSelector)) {
			options.after = scope.expandLinkSelector;
		}

		element.dotdotdot(options);

		element.on('$destroy', function () {
			element.trigger('destroy');
		});
	};

	return {
		restrict: 'A',
		scope: {
			expandLinkSelector: '=expandLinkSelector'
		},
		link: link
	};
});