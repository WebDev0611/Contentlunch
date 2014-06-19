launch.module.controller('ForumThreadController',
        ['$scope', '$rootScope', 'AuthService', '$routeParams', '$q', 'Restangular', '$location', 'NotificationService', 
function ($scope,   $rootScope,   AuthService,   $routeParams,   $q,   Restangular,   $location,   notify) {
    var user = $scope.user = AuthService.userInfo();

    var Thread  = Restangular.one('account', user.account.id).one('forum-thread', $routeParams.threadId);
    var Replies = Thread.all('reply');

    $q.all({
        replies : Replies.getList(),
        thread  : Thread.get(),
    }).then(function (responses) {
        angular.extend($scope, responses);
    }).catch(function (err) {
        $rootScope.globalErrorHandler(err);
        if (err.status == 404) $location.path('/consult/forum');
    });

    // Reply CRUD
    // -------------------------
    $scope.createReply = function (reply) {
        reply.forumThreadId = $routeParams.threadId;
        reply.userId = user.id;

        Replies.post(reply).then(function (reply) {
            notify.success('Reply created');
            _.appendOrUpdate($scope.replies, reply);
        }).catch($rootScope.globalErrorHandler);
    };

    $scope.deleteReply = function (reply) {
        reply.remove().then(function () {
            notify.success('Reply Deleted');
            _.remove($scope.replies, reply);
        }).catch($rootScope.globalErrorHandler);
    };

    $scope.updateReply = function (reply) {
        reply.put().then(function (reply) {
            notify.success('Reply updated');
            _.appendOrUpdate($scope.replies, reply);
        }).catch($rootScope.globalErrorHandler);
    };

}]);