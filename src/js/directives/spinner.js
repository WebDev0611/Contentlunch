angular.module('launch')

.directive('spinner', [function() {
    return {
        restrict: 'EA',
        transclude: true,
        replace: true,
        template: '<div ng-transclude class="loading-spinner"></div>',
        scope: {
            config: '=spin',
            spinif: '=spinIf'
        },
        link: function (scope, element, attrs) {

            var defaults = { 
                lines: 11, 
                length: 3, 
                width: 2, 
                radius: 4, 
                corners: 1.0, 
                rotate: 0, 
                trail: 60, 
                speed: 1.0, 
                direction: 1 
            };

            var config = _.defaults((scope.config || {}), defaults);

            var spinner = new Spinner(config),
                stopped = false;
            spinner.spin(element[0]);

            scope.$watch('config', function (newValue, oldValue) {
                if (newValue == oldValue)
                    return;
                spinner.stop();
                spinner = new Spinner(newValue);
                if (!stopped)
                    spinner.spin(element[0]);
            }, true);

            if (attrs.hasOwnProperty('spinIf')) {
                scope.$watch('spinif', function (newValue) {
                    if (newValue) {
                        spinner.spin(element[0]);
                        stopped = false;
                    } else {
                        spinner.stop();
                        stopped = true;
                    }
                });
            }

            scope.$on('$destroy', function() {
                spinner.stop();
            });
        }
    };
}]);