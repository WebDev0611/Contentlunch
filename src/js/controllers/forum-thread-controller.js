launch.module.controller('ForumThreadController',
        ['$scope', '$rootScope', 'AuthService', '$routeParams', '$q', 'Restangular', '$location', 'NotificationService', 
function ($scope,   $rootScope,   AuthService,   $routeParams,   $q,   Restangular,   $location,   notify) {
    var user = $scope.user = AuthService.userInfo();
    $scope.user.name = $scope.user.displayName;

    var Thread  = Restangular.one('forum-thread', $routeParams.threadId);
    var Replies = Thread.all('reply');

    $scope.canCreate = user.hasPrivilege('consult_execute_forum_create');

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
        reply.accountId = (user.account || {}).id;

        Replies.post(reply).then(function (newReply) {
            reply.body = '';
            notify.success('Reply created');
            _.appendOrUpdate($scope.replies, newReply);
        }).catch($rootScope.globalErrorHandler);
    };

    $scope.deleteReply = function (reply) {
        reply.remove().then(function () {
            notify.success('Reply Deleted');
            _.remove($scope.replies, reply);
        }).catch($rootScope.globalErrorHandler);
    };

    $scope.updateReply = function (reply) {
        Restangular.copy(reply).put().then(function (newReply) {
            $scope.isEditing = false; 
            $scope.current.reply = {};
            notify.success('Reply updated');
            _.appendOrUpdate($scope.replies, newReply);
        }).catch($rootScope.globalErrorHandler);
    };

    $scope.isEditing = false; 
    $scope.current = { reply: {} };
    $scope.editReply = function (reply) {
        $scope.current.reply = Restangular.copy(reply);
        reply.isEditing = $scope.isEditing = true;
    };

    $scope.cancelReply = function (reply) {
        $scope.current.reply = {};
        reply.isEditing = $scope.isEditing = false;
    };

    // Helpers
    // -------------------------
    $scope.pagination = {
        pageSize: 10,
        currentPage: 1,
    };
}]);