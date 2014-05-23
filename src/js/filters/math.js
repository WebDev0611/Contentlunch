angular.module('launch')

// input:   num | min:num2:num3:...:numN
// returns: min of all numbers passed in
.filter('min', [function () {
    return function () {
        var args = _.toArray(arguments);
        return Math.min.apply(null, args);
    }; 
}])

// input:   num | mix:num2:num3:...:numN
// returns: mix of all numbers passed in
.filter('max', [function () {
    return function () {
        var args = _.toArray(arguments);
        return Math.max.apply(null, args);
    }; 
}]);