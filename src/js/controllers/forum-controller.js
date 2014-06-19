launch.module.controller('ForumController',
        ['$scope', 'AuthService', '$routeParams', '$filter', '$q', '$upload', 'Restangular', '$location', '$rootScope', 'campaignTasks', 'NotificationService', 
function ($scope,   AuthService,   $routeParams,   $filter,   $q,   $upload,   Restangular,   $location,   $rootScope,   campaignTasks,   notify) {
    var user = $scope.user = AuthService.userInfo();

    var Threads = Restangular.one('account', user.account.id).all('forum-thread');

    $q.all({
        threads: Threads.getList(),
    }).then(function (responses) {
        angular.extend($scope, responses);
    });

    var defaultThread = {
        userId: user.id,
        description: 'This is the description',
        name: 'Test Thread',
    };

    $scope.createThread = function (thread) {
        thread = thread || defaultThread;

        Threads.post(thread).then(function (thread) {
            console.log(thread);
            notify.success('Thread created');
            _.appendOrUpdate($scope.threads, thread);
        }).catch($rootScope.globalErrorHandler);
    };
}]);