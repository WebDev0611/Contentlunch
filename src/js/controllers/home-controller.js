launch.module.controller('HomeController', 
            ['$scope', '$rootScope', '$location', 'AuthService', '$q', 'NotificationService', 'Restangular', 
    function ($scope,   $rootScope,   $location,   AuthService,   $q,   notify,                Restangular) {
        $scope.isLoaded = false;

        var user = AuthService.userInfo();

        // Restangular Models
        var Account = Restangular.one('account', user.account.id);
        var Discussion = Account.all('discussion');
        var User = Restangular.one('user', user.id);
        var Announcements = Restangular.all('announcements');

        $q.all({
            discussion: Discussion.getList(),
            tasks: User.getList('tasks'),
            announcements: Announcements.getList(),
            user: User.get(),
            guests: Account.all('guest-collaborators').getList({ limit: 5 }),
            // contentStrategy: Account.customGET('content-strategy'),
            brainstorms: Account.all('brainstorm').getList({ user: user.id })
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

        // Announcements
        // -------------------------
        $scope.hideAnnouncement = function (ids) {
            if (!_.isArray(ids)) ids = [ids];

            $scope.user.hiddenAnnouncements = _.union( $scope.user.hiddenAnnouncements, ids);
            $scope.user.put().catch($rootScope.globalErrorHandler);

            _.each(ids, function (id) {
                _.remove($scope.announcements, id);
            });
        };

        $scope.hideAllAnnouncements = function () {
            $scope.hideAnnouncement(_.pluck($scope.announcements, 'id'));
        };

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