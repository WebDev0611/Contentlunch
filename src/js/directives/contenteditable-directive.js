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

			$(element).focus(function(e) {
				$(this).bind('mouseup', function (ev) {
					event.preventDefault();
					$(this).unbind('mouseup');
				});

				launch.utils.selectAllText(this);
			});

			// view -> model
			element.on('blur', function () {
				scope.$apply(function () {
					if (!(element.html() === ctrl.$viewValue)) {
						ctrl.$setViewValue(element.html());

						if ($.isFunction(scope.onchange)) {
							scope.onchange(element.html());
						}
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