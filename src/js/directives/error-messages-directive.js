
launch.module.directive('errorMessage', function($compile, $templateCache) {
	return {
		restrict: 'A',
		scope: {
			message: '=message'
		},
		link: function (scope, element, attrs) {
			scope.$watch('message', function (msg) {
				if (launch.utils.isBlank(msg)) {
					$(element).hide();
				} else {
					$(element).show();
					$(element).html('<span class="glyphicon glyphicon-warning-sign"></span> <span>' + msg + '</span>');
				}
			});
		}
	};
});