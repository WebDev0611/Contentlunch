angular.module('launch')

// input:   date | isOverdue
// returns: true if today > date, false otherwise
.filter('isOverdue', [function () {
    return function (date) {
        return moment(date).diff(moment().startOf('day')) < 0;
    }; 
}]);