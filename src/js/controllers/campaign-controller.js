launch.module.controller('CampaignController',
        ['$scope', 'AuthService', '$routeParams', '$filter', '$q', 'Restangular', '$location', '$rootScope', 'campaignTasks', 'NotificationService', 
function ($scope,   AuthService,   $routeParams,   $filter,   $q,   Restangular,   $location,   $rootScope,   campaignTasks,   notify) {
    var user = $scope.user = AuthService.userInfo();
    var Account   = Restangular.one('account', user.account.id);
    var Campaigns = Account.all('campaigns');
    var Campaign  = Campaigns.one($routeParams.campaignId);

    $q.all({
        campaign: $routeParams.campaignId === 'new' ? newCampaign() : Campaign.get(),
        campaignTypes: Restangular.all('campaign-types').getList(),
        users: Account.all('users').getList(),
        tasks: Campaign.getList('tasks')
    }).then(function (responses) {
        console.log(_.mapObject(responses, function (response, key) {
            return [key, response.plain ? response.plain() : response];
        }));
        angular.extend($scope, responses);
        // when did ngModel with checkboxes get so strict?
        $scope.campaign.photoNeeded = !!$scope.campaign.photoNeeded;
        $scope.campaign.linkNeeded = !!$scope.campaign.linkNeeded;
        $scope.campaign.isSeries = !!$scope.campaign.isSeries;
        if (!$scope.campaign) {
            notify.error('Campaign does not exist');
            $scope.cancelCampaign();
        }
    }).catch($rootScope.globalErrorHandler);

    // Actions
    // -------------------------
    $scope.saveCampaign = function (campaign) {
        (campaign.isNew ? Campaigns.post(campaign) : campaign.put()).then(function (campaign) {
            var path = $location.path();
            notify.success('Campaign saved');
            if (path.match(/new\/?$/)) {
                path = path.replace(/new\/?$/, campaign.id);
                $location.path(path);
            } else {
                $scope.campaign = campaign;
            }
        }).catch($rootScope.globalErrorHandler);
    };

    $scope.deleteCampaign = function (campaign) {
        if (campaign.isNew) return $scope.cancelCampaign();
        campaign.remove().then(function () {
            notify.success('Campaign deleted');
            $scope.cancelCampaign();
        }).catch($rootScope.globalErrorHandler);
    };

    $scope.cancelCampaign = function () {
        $location.path('/calendar');
    };

    // Collaborator Actions //
    $scope.addCollaborator = function (collab) {
        $scope.showAddInternal = false;
        if (!_.isArray($scope.campaign.collaborators)) 
            $scope.campaign.collaborators = [];

        $scope.campaign.all('collaborators').post({ 
            userId: collab.id 
        }).then(function () {
            $scope.campaign.collaborators.push(collab);
        });
    };

    $scope.removeCollaborator = function (collab) {
        $scope.campaign.one('collaborators', collab.id).remove().then(function () {
            $rootScope.removeRow($scope.campaign.collaborators, collab.id);
        });
    };

    // Task Actions //
    $scope.newTask = function () {
        campaignTasks.openModal($scope.tasks).then(function (tasks) {
            $scope.tasks = tasks;
        });
    };

    $scope.editTask = function (task) {
        campaignTasks.openModal($scope.tasks, task).then(function (tasks) {
            if (tasks) $scope.tasks = tasks;
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
        if (!($scope.campaign || {}).campaignTypeId) return false;

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

        return _.contains(validIds, $scope.campaign.campaignTypeId);
    };

    function newCampaign() {
        return {
            isNew: true,
            accountId: user.account.id,
            userId: user.id,
            // startDate: moment().format(),
            // endDate: moment().add('month', 1).format(),
            // put any other defaults needed here
        };
    }
}]);