angular.module('launch')

// this one helps checkboxes to use 1/0 as true/false
// i.e. the server passes back 1 for true
.directive('checkInt', [function () {
    return {
        require: '?ngModel',
        priority: 1, // this needs to execute before Angular's
        link: function (scope, elems, attrs, ngModel) {
            if (!ngModel || attrs.type !== 'checkbox' || attrs.ngTrueValue) return;

            // this allows any truthy value to be initally passed in
            // to the checkbox and it will still show up as checked
            ngModel.$setViewValue(!!scope.$eval(attrs.ngModel));
            ngModel.$render();
        }
    };
}]);