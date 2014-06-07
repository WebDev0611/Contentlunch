launch.module.controller('CampaignController',
        ['$scope', 'AuthService', '$routeParams', '$filter', '$q', 'Restangular', '$location', '$rootScope', 'NotificationService', 
function ($scope,   AuthService,   $routeParams,   $filter,   $q,   Restangular,   $location,   $rootScope,   notify) {
    var user     = AuthService.userInfo();
    var Account  = Restangular.one('account', user.account.id);
    var Campaign = Account.all('campaigns');

    $q.all({
        campaign: $routeParams.campaignId === 'new' ? newCampaign() : Campaign.get($routeParams.campaignId),
        campaignTypes: Restangular.all('campaign-types').getList(),
        users: Account.all('users').getList()
    }).then(function (responses) {
        angular.extend($scope, responses);
        if (!$scope.campaign) {
            notify.error('Campaign does not exist');
            $scope.cancelCampaign();
        }
    }).catch($rootScope.globalErrorHandler);

    // Actions
    // -------------------------
    $scope.saveCampaign = function (campaign) {
        (campaign.isNew ? Campaign.post(campaign) : campaign.put()).then(function (campaign) {
            var path = $location.path();
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
    $scope.addInternalCollaborator = function (collaboratorToAdd) {
        $scope.showAddInternal = false;
        if (!_.isArray($scope.selected.internalCollaborators)) 
            $scope.selected.internalCollaborators = [];

        $scope.selected.all('collaborators').post({ 
            userId: collaboratorToAdd.id 
        }).then(function () {
            $scope.selected.internalCollaborators.push(collaboratorToAdd);
        });
    };

    $scope.removeInternalCollaborator = function (collab) {
        $scope.selected.one('collaborators', collab.id).remove().then(function () {
            $rootScope.removeRow($scope.selected.internalCollaborators, collab.id);
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
            collaborators: [user],
            // startDate: moment().format(),
            // endDate: moment().add('month', 1).format(),
            // put any other defaults needed here
        };
    }
}]);