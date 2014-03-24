launch.module.directive('errorMessage', function($compile, $templateCache) {
	return {
		restrict: 'A',
		scope: {
			message: '=message'
		},
		link: function (scope, element, attrs) {
			scope.$watch('message', function (msg) {
				var id = ((!!$(element)[0].id) ? $(element)[0].id : $(element)[0].name) + '_msg';

				$('#' + id).remove();

				if (!launch.utils.isBlank(msg)) {
					$(element).before('<div id="' + id + '" class="error-message"><span class="glyphicon glyphicon-warning-sign"></span> <span>' + msg + '</span></div>');
				}
			});


			//var options = {
			//	content: null,
			//	placement: element.data('placement') || 'top',
			//	trigger: 'manual',
			//	html: true,
			//	delay: { hide: 250 },
			//	container: element.data('container') || 'body'
			//};

			//scope.close = function () {
			//	$(element).popover('hide');
			//	$(element).popover('destroy');
			//};

			//scope.$watch('message', function (msg) {
			//	if (!launch.utils.isBlank(msg)) {
			//		options.content = '<div class="error-message">' + msg + '</div>';
			//		options.title = '<div class="error-message"><span class="glyphicon glyphicon-warning-sign"></span> Error!</div><span class="glyphicon glyphicon-remove"></span>';

			//		$(element).popover(options);

			//		$(element).on('shown.bs.popover', function () {
			//			$('.popover-title .glyphicon-remove').click(function (e) {
			//				scope.close();
			//				e.stopImmediatePropagation();
			//			});
			//		});

			//		$(element).popover('show');
			//	} else {
			//		scope.close();
			//	}
			//});
		}
	};
});