launch.module.directive('contenteditable', function () {
	return {
		require: 'ngModel',
		scope: {
			onchange: '=onChange',
			placeholder: '=placeHolder'
		},
		link: function (scope, element, attrs, ctrl, ngModel) {
			if (!launch.utils.isBlank(scope.placeholder)) {
				$(element).attr('data-placeholder', scope.placeholder);
			}

			// view -> model
			element.on('blur', function () {
				scope.$apply(function() {
					ctrl.$setViewValue(element.html());

					if ($.isFunction(scope.onchange)) {
						scope.onchange(element.html());
					}
				});
			});

			// model -> view
			ctrl.$render = function() {
				element.html(ctrl.$viewValue);
			};
		}
	};
});