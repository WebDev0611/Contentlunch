angular.module('launch')

// input:   <div ng-bind-html="string | nl2br"></div>
// returns: a version of the string with the newlines
//          replaced with html breaks (<br>)
.filter('nl2br', [function () {
    return function (input) {
        if (!angular.isString(input)) return input;
        return input.replace(/\n/g, '<br>');
    }; 
}]);