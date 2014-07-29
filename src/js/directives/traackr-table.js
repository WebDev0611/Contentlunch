angular.module('launch')
.directive('traackrTable', [function () {
    return {
        restrict: 'AE', // E = Element, A = Attribute, C = Class, M = Comment
        scope: { users: '=', selectedIds: '=?', tagged: '=' },
        templateUrl: '/assets/views/collaborate/traackr-table.html',
        link: function (scope, elem, attrs) {
            if (!scope.selectedIds) scope.selectedIds = [];

            scope.isTagged = function (user) {
                if (scope.tagged === true) return true;
                return _.find(scope.tagged, { uid: user.uid });
            };
        }
    };
}]);