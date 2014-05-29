// adapted from https://github.com/UnicodeSnowman/angular-paginate-filter
// currentPage is 1 indexed
angular.module('launch')

.filter('paginate', ['$filter', function ($filter) {
    return function (input, currentPage, pageSize) {
        if (input && input.length) {
            return $filter('limitTo')(input.slice((currentPage - 1) * pageSize), pageSize);
        }
    }; 
}]);