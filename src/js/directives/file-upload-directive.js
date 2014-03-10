//launch.module.directive('fileUpload', function () {
//	var self = this;

//	self.link = function (scope, element, attrs) {
//		element.bind('change', function (event) {
//			if ($.isFunction(scope.onSelectFile)) {
//				if (!scope.onSelectFile(event.target.files)) {
//					$(element).replaceWith(element = $(element).clone(true, true));
//				}
//			}
//		});
//	};

//	return {
//		restrict: 'A',
//		scope: {
//			onSelectFile: '=onSelectFile'
//		},
//		link: self.link
//	};
//});