launch.module.controller('ForumController',
        ['$scope', '$rootScope', 'AuthService', '$routeParams', '$q', 'Restangular', 'NotificationService', 
function ($scope,   $rootScope,   AuthService,   $routeParams,   $q,   Restangular,   notify) {
    var user = $scope.user = AuthService.userInfo();

    var Threads = Restangular.one('account', user.account.id).all('forum-thread');

    $q.all({
        threads: Threads.getList(),
    }).then(function (responses) {
        angular.extend($scope, responses);
    }).catch($rootScope.globalErrorHandler);

    // Thread CRUD
    // -------------------------
    $scope.createThread = function (thread) {
        thread.userId = user.id;

        Threads.post(thread).then(function (thread) {
            notify.success('Thread created');
            _.appendOrUpdate($scope.threads, thread);
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
}]);