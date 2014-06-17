angular.module('launch')

.directive('sortBy', [function() {
    return {
        scope: { sort: '=sortVar', predicate: '@sortBy' },
        link: function (scope, elem, attrs) {
            console.log('test');
            elem.click(function () {
                console.log('click');
                scope.$apply(function () {
                    if (scope.sort == scope.predicate) {
                        scope.sort = reverseOrder(scope.sort);
                    } else {
                        scope.sort = scope.predicate;
                    }
                });
            });

            elem.addClass('fa');

            scope.$watch('sort', function (sort) {
                var predicate = extractPredicate(sort);
                if (predicate == scope.predicate) {
                    if (isReversed(sort)) {
                        elem.removeClass('fa-caret-up').addClass('fa-caret-down');
                    } else {
                        elem.removeClass('fa-caret-down').addClass('fa-caret-up');
                    }
                } else {
                    elem.removeClass('fa-caret-down fa-caret-up');
                }
            });
        }
    };

    // I know we could have a reverse variable
    // but I wanted to keep the markup simpler
    function reverseOrder(sort) {
        var predicate = extractPredicate(sort);
        return predicate == sort ? '-' + predicate : predicate;
    }

    function isReversed(sort) {
        var predicate = extractPredicate(sort);
        return predicate != sort;
    }

    function extractPredicate(sort) {
        return (sort || '').replace(/^-/, '');
    }
}]);        