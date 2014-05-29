angular.module('launch')

.directive('slideToggle', ['$parse', function ($parse) {
    return {
        link: function (scope, elem, attrs) {
            var open = $parse(attrs.slideToggle)(scope);
            if (!open) elem.hide();

            scope.$watch(attrs.slideToggle, function (open) {
                if (open) elem.slideDown();
                else elem.slideUp();
            });
        }
    };
}]);