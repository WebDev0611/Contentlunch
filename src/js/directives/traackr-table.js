angular.module('launch')
.directive('traackrTable', [function () {
    return {
        restrict: 'AE', // E = Element, A = Attribute, C = Class, M = Comment
        scope: { users: '=' },
        templateUrl: '/assets/views/collaborate/traackr-table.html',
        link: function (scope, elem, attrs) {}
    };
}]);