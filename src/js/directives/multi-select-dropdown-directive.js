launch.module.directive('multiSelectDropdown', function ($window) {
	var link = function(scope, element, attrs) {
		var select = $(element).children('select');
		var multiselect = null;

		scope.isSelected = function(listItem) {
			var match = $.grep(scope.model, function(item, i) {
				return item.toLowerCase() === listItem.toLowerCase();
			});

			return (match.length === 1);
		};

		if (scope.multiple === true) {
			select.attr('multiple', 'multiple');
			select.addClass('multiselect');

			$window.setTimeout(function() {
				multiselect = select.multiselect({
					onChange: function(option) {
						var exists = $.inArray(option[0].value, scope.model);

						if (option[0].selected === true && exists < 0) {
							scope.model.push(option[0].value);
						} else if (!option[0].selected && exists >= 0) {
							scope.model.splice(exists, 1);
						}
					}
				});
			}, 0);
		}

		scope.$watch('disabled', function () {
			if (scope.disabled === true) {
				select.prop('disabled', 'disabled');

				if (!!multiselect) { select.multiselect('disable'); }
			} else {
				select.removeAttr('disabled');

				if (!!multiselect) { select.multiselect('enable'); }
			}
		});

		element.on('$destroy', function() {
			if (!!multiselect) {
				select.multiselect('destroy');
			}
		});
	};

	return {
		link: link,
		scope: {
			controlLabel: '=controlLabel',
			controlName: '=controlName',
			listItems: '=listItems',
			multiple: '=multiple',
			disabled: '=disabled',
			model: '=model'
		},
		templateUrl: '/assets/views/multi-select-dropdown.html'
	};
});