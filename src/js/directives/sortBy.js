angular.module('launch')

.directive('sortBy', [function() {
    return {
        scope: { sort: '=sortVar', predicate: '@sortBy' },
        link: function (scope, elem, attrs) {
            elem.click(function () {
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
                    	elem.removeClass('fa-sort-alpha-asc').addClass('fa-sort-alpha-desc');
                    } else {
                    	elem.removeClass('fa-sort-alpha-desc').addClass('fa-sort-alpha-asc');
                    }
                } else {
                	elem.removeClass('fa-sort-alpha-desc fa-sort-alpha-asc');
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