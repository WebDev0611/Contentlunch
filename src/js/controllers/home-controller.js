launch.module.controller('HomeController', 
            ['$scope', '$rootScope', '$location', 'AuthService', '$q', 'NotificationService', 'Restangular', 
    function ($scope,   $rootScope,   $location,   AuthService,   $q,   notify,                Restangular) {
        $scope.isLoaded = false;

        var user = $scope.user = AuthService.userInfo();
        $scope.user.name = $scope.user.displayName;

        // Restangular Models
        var Account = Restangular.one('account', $scope.user.account.id);
        var Discussion = Account.all('discussion');

        $q.all({
            discussion: Discussion.getList(),
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