launch.module.controller('ForumController',
        ['$scope', '$rootScope', 'AuthService', '$routeParams', '$q', 'Restangular', 'NotificationService', 
function ($scope,   $rootScope,   AuthService,   $routeParams,   $q,   Restangular,   notify) {
    var user = $scope.user = AuthService.userInfo();
    $scope.user.name = $scope.user.displayName;
    $scope.showNewThreadForm = false;

    $scope.canCreate = user.hasPrivilege('consult_execute_forum_create');

    var Threads = Restangular.all('forum-thread');

    $q.all({
        threads: Threads.getList(),
    }).then(function (responses) {
        angular.extend($scope, responses);
    }).catch($rootScope.globalErrorHandler);

    // Thread CRUD
    // -------------------------
    $scope.createThread = function (thread) {
        thread.userId = user.id;
        thread.accountId = (user.account || {}).id;

        Threads.post(thread).then(function (thread) {
            notify.success('Thread created');
            _.appendOrUpdate($scope.threads, thread);
            $scope.cancelThread();
        }).catch($rootScope.globalErrorHandler);
    };

    $scope.deleteThread = function (thread) {
        thread.remove().then(function () {
            notify.success('Thread Deleted');
            _.remove($scope.threads, thread);
        }).catch($rootScope.globalErrorHandler);
    };

    $scope.updateThread = function (thread) {
        thread.put().then(function (thread) {
            notify.success('Thread updated');
            _.appendOrUpdate($scope.threads, thread);
        }).catch($rootScope.globalErrorHandler);
    };

    $scope.cancelThread = function () {
        $scope.thread = {};
        $scope.showNewThreadForm = false;
    };

    // Helpers
    // -------------------------
    $scope.pagination = {
        pageSize: 10,
        currentPage: 1,
    };
}]);