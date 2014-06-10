angular.module('launch')

.directive('datepickerPopup', [function () {
    return {
        require: '?ngModel',
        link: function (scope, elems, attrs, ngModel) {
            if (!ngModel) return;
            ngModel.$formatters.push(function (date) {
                if (!date) return;
                // moment date formats don't match ui-bootstrap's :-/
                return moment(date).format('MM/DD/YYYY');
            });

            if (attrs.dateOnly === true || attrs.dateOnly === 'true') {
                ngModel.$parsers.push(function (date) {
                    if (!date) return;
                    return moment(date).format('YYYY-MM-DD');
                });
            }
        }
    };
}]);