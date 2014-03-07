launch.module.directive('fileUpload', function () {
	var self = this;

	self.link = function (scope, element, attrs) {
		element.bind('change', function (event) {
			if ($.isFunction(scope.onSelectFile)) {
				scope.onSelectFile(event.target.files);
			}
		});
	};

	return {
		restrict: 'A',
		scope: {
			onSelectFile: '=onSelectFile'
		},
		link: self.link
	};
});