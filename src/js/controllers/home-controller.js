///<reference path="../launch.ts"/>
var launchts;
(function (launchts) {
    var HomeController = (function () {
        function HomeController($scope, $rootScope, $location, AuthService, $q, notify, measureService, Restangular, user, accountId) {
            this.$scope = $scope;
            this.$rootScope = $rootScope;
            this.$location = $location;
            this.AuthService = AuthService;
            this.$q = $q;
            this.notify = notify;
            this.measureService = measureService;
            this.Restangular = Restangular;
            this.user = user;
            $scope.isLoaded = false;
            // If user is global admin, we don't really have a homepage
            // for them so take them to /accounts
            //        This is done in app.js now at the route level. was causing errors before
            //        if (user && launch.utils.isBlank(user.account)) {
            //            window.location.href = '/accounts';
            ////            $location.path('/accounts');
            //            return;
            //        }
            if (!user || !user.account || launch.utils.isBlank(user.account.id)) {
                $location.path('/login');
                return;
            }
            // Restangular Models
            var Account = Restangular.one('account', accountId);
            var Discussion = Account.all('discussion');
            var User = Restangular.one('user', user.id);
            var Announcements = Restangular.all('announcements');
            $scope.user = user;
            $q.all({
                discussion: Discussion.getList(),
                tasks: User.getList('tasks'),
                announcements: Announcements.getList(),
                guests: Account.all('guest-collaborators').getList({ limit: 5 }),
                strategy: user.account.strategy,
                brainstorms: Account.all('brainstorm').getList({ user: user.id }),
                myActivity: Account.getList('my-activity'),
                allActivity: Account.getList('all-activity')
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
            $scope.showUnreadCount = true;
            $scope.markMyActivityAsRead = function () {
                $scope.myActivity.post();
                $scope.showUnreadCount = false;
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
                if (!_.isArray(ids))
                    ids = [ids];
                $scope.user.hiddenAnnouncements = _.union($scope.user.hiddenAnnouncements, ids);
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
                }
                else {
                    return '/calendar/campaigns/' + task.campaign.id;
                }
            };
            $scope.getBrainstormUrl = function (brainstorm) {
                if (brainstorm.contentId) {
                    return '/create/concept/edit/content/' + brainstorm.contentId;
                }
                else {
                    return '/calendar/concept/edit/campaign/' + brainstorm.campaignId;
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
                currentPage: 1
            };
            $scope.getChartData = function () {
                var startDate;
                if (!this.days) {
                    startDate = moment('1970-01-01');
                }
                else if (this.days == 'Q') {
                    startDate = moment().startOf('quarter');
                }
                else if (this.days == 'Y') {
                    startDate = moment().startOf('year');
                }
                else if (this.days == 'A') {
                    startDate = moment().startOf('year');
                }
                else {
                    startDate = moment().subtract(parseInt(this.days), 'days');
                }
                return this.info.measureFunction(user.account.id, startDate.format('YYYY-MM-DD'));
            };
            $scope.formatActivity = function (activity) {
                if (launch.utils.startsWith(activity.activity, 'assigned') || launch.utils.startsWith(activity.activity, 'added as')) {
                    var person = (user.id === activity.userId) ? 'You were' :
                        (!!activity.user ? activity.user.firstName + ' ' + activity.user.lastName : 'Someone') + ' was';
                    return person + ' ' + activity.activity + ' ' + activity.content.title + ' on ' + launch.utils.formatDate(activity.createdAt);
                }
                return activity.content.title + ' was ' + activity.activity + ' on ' + launch.utils.formatDate(activity.createdAt);
            };
            this.initChartData();
        }
        HomeController.prototype.initChartData = function () {
            var _this = this;
            function fieldDateParse(field) {
                return function (date, groupBy, group) {
                    var sum = 0;
                    if (groupBy == 'all' || !groupBy) {
                        $.each(date.stats.by_user, function (i, user) {
                            sum += parseFloat(user[field]);
                        });
                    }
                    else if (groupBy == 'author') {
                        $.each(date.stats.by_user, function (i, user) {
                            if (user.user_id == group) {
                                sum += parseFloat(user[field]);
                            }
                        });
                    }
                    else if (groupBy == 'buying-stage') {
                        $.each(date.stats.by_buying_stage, function (i, stage) {
                            if (stage.buying_stage == group) {
                                sum += parseFloat(stage[field]);
                            }
                        });
                    }
                    else if (groupBy == 'content-type') {
                        $.each(date.stats.by_content_type, function (i, type) {
                            if (type.content_type_id == group) {
                                sum += parseFloat(type[field]);
                            }
                        });
                    }
                    return { label: date.date, data: sum };
                };
            }
            this.$scope.companyContentScoreTime = 7;
            this.$scope.companyContentScoreLine = {
                title: 'Company Content Score',
                measureFunction: this.measureService.getScore,
                dateParseFunction: fieldDateParse('score')
            };
            this.$scope.totalContentItemsTime = 7;
            this.$scope.totalContentItemsLine = {
                title: 'Total Content Items',
                measureFunction: this.measureService.getCreated,
                dateParseFunction: fieldDateParse('count')
            };
            this.$scope.stageBreakdownPie = {
                title: 'Stage Breakdown',
                measureFunction: function () {
                    return [];
                },
                dateParseFunction: function () {
                    return [];
                }
            };
            this.$scope.overview = this.measureService.getOverview(this.user.account.id, {
                success: function () { },
                error: function (r) {
                    launch.utils.handleAjaxErrorResponse(r, _this.notify);
                } });
        };
        HomeController.$inject = ['$scope',
            '$rootScope',
            '$location',
            'AuthService',
            '$q',
            'NotificationService',
            'MeasureService',
            'Restangular',
            'userInfo',
            'accountId'];
        return HomeController;
    })();
    launch.module.controller('HomeController', HomeController);
})(launchts || (launchts = {}));
//# sourceMappingURL=home-controller.js.map