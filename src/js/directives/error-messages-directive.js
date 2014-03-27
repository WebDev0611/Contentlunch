launch.module.directive('errorMessage', function($compile, $templateCache) {
	return {
		restrict: 'A',
		scope: {
			message: '=message'
		},
		link: function (scope, element, attrs) {
			scope.$watch('message', function (msg) {
				var control = null;
				var index = launch.utils.isBlank(element.data('index')) ? '' : element.data('index');

				if (element[0].tagName === 'INPUT' || element[0].tagName === 'SELECT' || element[0].tagName === 'TEXTAREA') {
					control = $(element)[0];
				} else {
					var controls = $(element).children('input');

					if (controls.length === 0) { controls = $(element).children('select'); }
					if (controls.length === 0) { controls = $(element).children('textarea'); }

					control = controls[0];
				}

				var id = ((!!control.id) ? control.id : control.name) + '_msg' + index;

				$('#' + id).remove();

				if (!launch.utils.isBlank(msg)) {
					// TODO: NEED TO FIND IF ELEMENT HAS A PARENT WITH CLASS 'input-group' AND IF SO, INSERT THE MESSAGE BELOW BEFORE THAT ELEMENT INSTEAD OF THE CONTROL!!
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