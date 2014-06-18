launch.module.controller('ForumController',
        ['$scope', 'AuthService', '$routeParams', '$filter', '$q', '$upload', 'Restangular', '$location', '$rootScope', 'campaignTasks', 'NotificationService', 
function ($scope,   AuthService,   $routeParams,   $filter,   $q,   $upload,   Restangular,   $location,   $rootScope,   campaignTasks,   notify) {
    var user = $scope.user = AuthService.userInfo();

}]);