angular.module('launch')

// input:   array | exclude:excludeArray:true
// returns: copy of original array that don't items from excludeArray.
//          check's against IDs unless checkValues == true
.filter('exclude', [function () {
    return function (array, excludeArray, checkValues) {
        // return _.difference(array, excludeArray);
        if (!checkValues) {
            array2 = _.pluck(array, 'id');
            excludeArray2 = _.pluck(excludeArray, 'id');
        } else {
            array2 = array;
            excludeArray2 = excludeArray;
        }

        var diff = _.difference(array2, excludeArray2);

        return checkValues ? diff : _.map(diff, function (id) {
            return _.findById(array, id);
        });
    }; 
}]);