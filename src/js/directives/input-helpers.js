angular.module('launch')

// this one helps checkboxes to use 1/0 as true/false
// i.e. the server passes back 1 for true
.directive('checkInt', ['$parse', function ($parse) {
    return {
        require: '?ngModel',
        priority: 1, // this needs to execute before Angular's
        link: function (scope, elem, attrs, ngModel) {
            if (!ngModel || attrs.type !== 'checkbox' || attrs.ngTrueValue) return;
            var firstTime = true;

            // this allows any truthy value to be initally passed in
            // to the checkbox and it will still show up as checked
            scope.$watch(attrs.ngModel, function (val) {
                if (val === '0') val = 0;

                elem.prop('checked', !!val);
            });
        }
    };
}]);
