launch.module.directive('errorMessage', function($compile, $templateCache) {
	return {
		restrict: 'A',
		scope: {
			//message: '=message',
			item: '=errorMessageItem',
			property: '=errorMessageProperty',
			formElement: '=errorMessageControl'
		},
		link: function (scope, element, attrs) {
			scope.$watch('item.' + scope.property, function(newItem, oldItem) {
				var msg = launch.utils.getPropertyErrorMessage(scope.item, scope.property, scope.formElement);

				var control = null;
				var index = launch.utils.isBlank(element.data('index')) ? '' : element.data('index');

				if (element[0].tagName === 'INPUT' || element[0].tagName === 'SELECT' || element[0].tagName === 'TEXTAREA') {
					control = $(element)[0];
				} else {
					var controls = $(element).children('input');

					if (controls.length === 0) {
						controls = $(element).children('select');
					}
					if (controls.length === 0) {
						controls = $(element).children('textarea');
					}

					control = controls[0];
				}

				var id = ((!!control.id) ? control.id : control.name) + '_msg' + index;

				$('#' + id).remove();

				var label = $('label[for="' + element[0].id + '"]');

				if (!launch.utils.isBlank(msg)) {
					if (label.length === 1) {
						label.after('<div id="' + id + '" class="error-message"><span class="glyphicon glyphicon-warning-sign"></span> <span>' + msg + '</span></div>');
					} else {
						element.before('<div id="' + id + '" class="error-message"><span class="glyphicon glyphicon-warning-sign"></span> <span>' + msg + '</span></div>');
					}
				}
			}, true);

			//scope.$watch('message', function() {
			//	var control = null;
			//	var index = launch.utils.isBlank(element.data('index')) ? '' : element.data('index');

			//	if (element[0].tagName === 'INPUT' || element[0].tagName === 'SELECT' || element[0].tagName === 'TEXTAREA') {
			//		control = $(element)[0];
			//	} else {
			//		var controls = $(element).children('input');

			//		if (controls.length === 0) {
			//			controls = $(element).children('select');
			//		}
			//		if (controls.length === 0) {
			//			controls = $(element).children('textarea');
			//		}

			//		control = controls[0];
			//	}

			//	var id = ((!!control.id) ? control.id : control.name) + '_msg' + index;

			//	$('#' + id).remove();

			//	var label = $('label[for="' + element[0].id + '"]');

			//	if (!launch.utils.isBlank(scope.message)) {
			//		if (label.length === 1) {
			//			label.after('<div id="' + id + '" class="error-message"><span class="glyphicon glyphicon-warning-sign"></span> <span>' + scope.message + '</span></div>');
			//		} else {
			//			element.before('<div id="' + id + '" class="error-message"><span class="glyphicon glyphicon-warning-sign"></span> <span>' + scope.message + '</span></div>');
			//		}
			//	}
			//});
		}
	};
});