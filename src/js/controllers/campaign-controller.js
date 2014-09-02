launch.module.controller('CampaignController',
        ['$scope', 'AuthService', '$routeParams', '$filter', '$q', '$upload', '$modal', 'Restangular', '$location', '$rootScope', 'campaignTasks', 'UserService', 'NotificationService',
function ($scope, AuthService, $routeParams, $filter, $q, $upload, $modal, Restangular, $location, $rootScope, campaignTasks, userService, notify) {
    var user = $scope.user = AuthService.userInfo();
    var Account   = Restangular.one('account', user.account.id);
    var Campaigns = Account.all('campaigns');

    $scope.isLoaded = false;
    $scope.isSaving = false;

    $q.all({
        campaign: $routeParams.campaignId === 'new' ? newCampaign() : Campaigns.get($routeParams.campaignId),
        campaignTypes: Restangular.all('campaign-types').getList(),
        users: Account.all('users').getList(),
        tasks: Campaigns.one($routeParams.campaignId).getList('tasks'),
        files: Campaigns.one($routeParams.campaignId).getList('uploads')
    }).then(function (responses) {
        angular.extend($scope, responses);

        if ($scope.campaign.status === 0) {
        	$location.path('/calendar/concept/edit/campaign/' + $scope.campaign.id);
        	return;
        }

        // help the UI with a few permission things
        if (user.id == $scope.campaign.userId) {
            $scope.canEdit = user.hasPrivilege('calendar_execute_campaigns_own');
        } else {
            $scope.canEdit = user.hasPrivilege('calendar_edit_campaigns_other');
        }

        if (!$scope.campaign) {
            notify.error('Campaign does not exist');
            $scope.cancelCampaign();
        }

        $scope.isLoaded = true;

        if ($location.search().duplicate) {
            $scope.duplicateCampaign();
        }

        $scope.filterCollaborators();
    }).catch($rootScope.globalErrorHandler);

    // Actions
    // -------------------------
    $scope.saveCampaign = function (campaign) {
        campaign.status = 1; // only concepts will have a non-1 status
        $scope.isSaving = true;
        (campaign.isNew ? Campaigns.post(campaign) : campaign.put()).then(function (camp) {
            var path = $location.path();
            notify.success('Campaign saved');
            if (campaign.isNew) {
                $location.search({}).path('/calendar/campaigns/' + camp.id);
            } else {
                $scope.campaign = camp;
            }
        }).catch($rootScope.globalErrorHandler).then(function () {
            $scope.isSaving = false;
        });
    };

    $scope.deleteCampaign = function (campaign) {
        if (campaign.isNew) return $scope.cancelCampaign();

        $modal.open({
            templateUrl: 'confirm.html',
            controller: ['$scope', '$modalInstance', function (_scope, instance) {
                    _scope.message = 'Are you sure want to delete this campaign?';
                    _scope.okButtonText = 'Delete';
                    _scope.cancelButtonText = 'Cancel';
                    _scope.onOk = function() {
                        campaign.remove().then(function () {
                            notify.success('Campaign deleted');
                            $scope.cancelCampaign();
                            instance.close();
                        }).catch($rootScope.globalErrorHandler);
                    };
                    _scope.onCancel = function() {
                        instance.dismiss('cancel');
                    };
                }
            ]
        });
    };

    $scope.cancelCampaign = function() {
        if ($scope.campaign.isNew) {
            $location.path('/calendar');
        } else {
            $scope.isLoaded = false;
            $scope.campaign = Campaigns.get($routeParams.campaignId).then(function(r) {
                $scope.isLoaded = true;
                    $scope.campaign = r;
                },
                function(r) {
                    notify.error(r);
                }
            );
        }
    };

    $scope.duplicateCampaign = function () {
        $location.search({ duplicate: true });

        // do what we need to do to clone this campaign
        delete $scope.campaign.id;
        delete $scope.campaign.startDate;
        delete $scope.campaign.endDate;

        $scope.campaign.isNew = true;
        $scope.campaign.title += ' (copy)';

        $scope.tasks = [];
        delete $scope.campaign.content;
    };

    // Task Actions //
    $scope.newTask = function () {
        campaignTasks.openModal($scope.tasks).then(function (tasks) {
            if (tasks) $scope.tasks = tasks;
            return $scope.campaign.getList('collaborators');
        }).then(function (collaborators) {
            if (collaborators) $scope.campaign.collaborators = collaborators;
        });
    };

    $scope.editTask = function (task) {
        campaignTasks.openModal($scope.tasks, task).then(function (tasks) {
            if (tasks) if (tasks) $scope.tasks = tasks;
            return $scope.campaign.getList('collaborators');
        }).then(function (collaborators) {
            if (collaborators) $scope.campaign.collaborators = collaborators;
        });
    };

    $scope.toggleTaskComplete = function (task) {
        task.dateCompleted = task.isComplete ? moment().format('YYYY-MM-DD') : null;
        task.put();
    };

    $scope.deleteTask = function (task) {
        task.remove().then(function () {
            _.remove($scope.tasks, task);
        });
    };

    // File Actions //
    $scope.selectFiles = function ($files) {
        _.each($files, function (file) {
            $upload.upload({
                url: $scope.campaign.all('uploads').getRestangularUrl(),
                method: 'POST',
                // data: data,
                file: file
            }).progress(function (event) {
                console.log('percent: ' + parseInt(100.0 * event.loaded / event.total));
            }).success(function (file) {
                _.appendOrUpdate($scope.files, file);
            });
            //.error(...)
        });
    };

    $scope.deleteFile = function (file) {
        $scope.campaign.one('uploads', file.id).remove().then(function () {
            _.remove($scope.files, file);
        });
    };

    $scope.filterCollaborators = function () {
        if (!$scope.campaign || !$scope.campaign.user) {
            return;
        }

        var users = userService.getForAccount(user.account.id);

        $scope.collaborators = $.grep(users, function (u) {
            return u.id !== $scope.campaign.user.id;
        });
    };

    // Helpers
    // -------------------------
    // TODO: make this more reusable
    $scope.formatUserItem = function (item, element, context) {
        if (!item.text) return element.attr('placeholder');
        var user = _.findById($scope.users, item.id)[0] || {};
        var style = ' style="background-image: url(\'' + $filter('imagePathFromObject')(user.image) + '\')"';

        return '<span class="user-image user-image-small"' + style + '></span> <span>' + item.text + '</span>';
    };

    // aw man, this kinda sucks
    $scope.shouldShow = function (column) {
        var id = ($scope.campaign || {}).campaignTypeId;
        if (!id) return false;
        id = parseInt(id);

        // 1  : Adertising Campaign
        // 2  : Branding Promotion
        // 3  : Content Marketing Campaign
        // 4  : Customer Nuture
        // 5  : Email Nuture Campaign
        // 6  : Event (Non Trade Show)
        // 7  : Partner Event
        // 8  : Podcast Series
        // 9  : Product Launch
        // 10 : Sales Campaign
        // 11 : Thought Leadership Series
        // 12 : Trade Show Event
        // 13 : Webinar

        var validIds = [];
        switch (column) {
            case 'speakerName':
                validIds = [6,8,12,13];
            break;
            case 'host':
                validIds = [12,13];
            break;
            case 'photoNeeded':
                validIds = [1,3,7];
            break;
            case 'linkNeeded':
                validIds = [1,3,7];
            break;
            case 'isSeries':
                validIds = [1,3,6,7];
            break;
            case 'type':
                validIds = [1,3];
            break;
            case 'audioLink':
                validIds = [8];
            break;
            default:
                console.error('Column not found: ' + column);
        }

        return _.contains(validIds, id);
    };

    function newCampaign() {
        return {
            isNew: true,
            accountId: user.account.id,
            userId: user.id,
			isActive: 1
            // startDate: moment().format(),
            // endDate: moment().add('month', 1).format(),
            // put any other defaults needed here
        };
    }
}]);