launch.module.directive('multiSelectDropdown', function ($window) {
	var link = function (scope, element, attrs) {
		var select = $(element).children('select');

		scope.isSelected = function(listItem) {
			var match = $.grep(scope.model, function (item, i) {
				return item.toLowerCase() === listItem.toLowerCase();
			});

			return (match.length === 1);
		};

		if (scope.multiple === true) {
			select.attr('multiple', 'multiple');
			select.addClass('multiselect');

			// TODO: IS THERE A BETTER WAY OF DOING THIS? SINCE THE OPTIONS IN THE DROP-DOWN LIST ARE NOT
			//			BOUND AT THIS POINT, IT'S TOO EARLY TO INITIALIZE THE MULTI-SELECT CONTROL.
			//			WE NEED TO INITIALIZE THIS CONTROL AFTER ALL THE CHILD ELEMENTS HAVE BEEN BOUND.
			window.setTimeout(function() {
				select.multiselect({
					onChange: function(option) {
						if (option[0].selected === true) {
							var match = $.grep(scope.model, function(item) {
								return item === option[0].value;
							});

							if (match.length === 0) {
								scope.model.push(option[0].value);
							}
						}
					}
				});
			}, 100);
		}
	};

	return {
		link: link,
		scope: {
			controlLabel: '=controlLabel',
			controlName: '=controlName',
			listItems: '=listItems',
			multiple: '=multiple',
			model: '=model'
		},
		templateUrl: '/assets/views/multi-select-dropdown.html'
	};
});