launch.module.controller('HomeController', 
            ['$scope', '$rootScope', '$location', 'AuthService', '$q', 'NotificationService', 'Restangular', 
    function ($scope,   $rootScope,   $location,   AuthService,   $q,   notify,                Restangular) {
        $scope.isLoaded = false;

        var user = $scope.user = AuthService.userInfo();
        $scope.user.name = $scope.user.displayName;

        // Restangular Models
        var Account = Restangular.one('account', $scope.user.account.id);
        var Discussion = Account.all('discussion');
        var User = Restangular.one('user', $scope.user.id);

        $q.all({
            discussion: Discussion.getList(),
            tasks: User.getList('tasks')
        }).then(function (responses) {
            angular.extend($scope, responses);
            $scope.isLoaded = true;
        });


        // Discussion
        // -------------------------
        $scope.createReply = function (reply) {
            reply.userId = user.id;
            reply.accountId = (user.account || {}).id;

            Discussion.post(reply).then(function (newReply) {
                reply.body = '';
                notify.success('Reply created');
                _.appendOrUpdate($scope.discussion, newReply);
            }).catch($rootScope.globalErrorHandler);
        };

        // Tasks
        // -------------------------
        // $scope.toggleTaskComplete = function (task) {
        //     task.dateCompleted = task.isComplete ? moment().format('YYYY-MM-DD') : null;
        //     task.put();
        // };

        $scope.getTaskUrl = function (task) {
            if (task.content) {
                return '/create/content/edit/' + task.content.id;
            } else {
                return '/calendar/campaigns/' + task.campaign.id;
            }
        };


        // Filters
        // -------------------------
        $scope.filters = {
            activity: {
                justMine: true
            }
        };

        // Paging
        // -------------------------
        $scope.pagination = {
            pageSize: 10,
            currentPage: 1,
        };
    }
]);