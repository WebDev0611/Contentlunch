angular.module('launch')

.directive('datepickerPopup', [function () {
    return {
        require: '?ngModel',
        link: function (scope, elems, attrs, ngModel) {
            if (!ngModel) return;
            ngModel.$formatters.push(function (date) {
                // console.log(date);
                return moment(date).format('MM/DD/YYYY');
            });
        }
    };
}]);