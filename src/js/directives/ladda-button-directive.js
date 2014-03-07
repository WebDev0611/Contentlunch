
launch.module.directive('laddaButton', function () {
	return {
		restrict: 'A',
		link: function (scope, element, attrs) {
			var ladda = Ladda.create(element[0]);

			scope.$watch(attrs.laddaButton, function (newVal, oldVal) {
				if (newVal) {
					ladda.start();
				} else {
					ladda.stop();
				}
			});
		}
	};
});