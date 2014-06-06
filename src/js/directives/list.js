angular.module('launch')

// Usage: <input type="text" list="tag" ng-model="tags">
// if it has a list attribute, it uses that to pluck/anti-pluck
// to and from the model. i.e. with the example above, you'd turn
// "one, two, three" into [{ tag: 'one' }, { tag: 'two' }, { tag: 'three' }] (and vice versa)
// and without passing in an attribute to `list`, you'd get ['one', 'two', 'three']
.directive('list', [function () {
    return {
        require: 'ngModel',
        link: function (scope, elem, attrs, ngModel) {
            var pluck = attrs.list;
            var toView = function (val) {
                if (pluck) val = _.pluck(val, pluck);
                return (val || []).join(', ');
            };
            
            var toModel = function (val) {
                return (val || '').split(',').map(function (v) {
                    v = v.trim();
                    return pluck ? _.object([[pluck, v]]) : v;
                });
            };
            
            ngModel.$formatters.unshift(toView);
            ngModel.$parsers.unshift(toModel);
        }
    };
}]);