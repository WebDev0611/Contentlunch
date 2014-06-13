angular.module('launch')

// input:   date | moment:'MM/DD/YYYY'
// returns: the date formatted with Moment
.filter('moment', [function () {
    return function (date, format) {
        return moment(date).format(format);
    }; 
}]);