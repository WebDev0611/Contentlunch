launch.module.controller('CampaignController',
        ['$scope', 'AuthService', '$routeParams', '$filter', '$q', 'Restangular',
function ($scope,   AuthService,   $routeParams,   $filter,   $q,   Restangular) {
    var user     = AuthService.userInfo();
    var Account  = Restangular.one('account', user.account.id);
    var Campaign = Account.all('campaigns');

    var campaignPromise = $routeParams.campaignId === 'new' ? { isNew: true } : Campaign.get($routeParams.campaignId);

    $q.all({
        campaign: campaignPromise,
        campaignTypes: Restangular.all('campaign-types').getList(),
        users: Account.all('users').getList()
    }).then(function (responses) {
        angular.extend($scope, responses);
    });

    // Actions
    // -------------------------
    $scope.saveCampaign = function (campaign) {
        (campaign.isNew ? Campaign.post(campaign) : campaign.put()).then(function (campaign) {
            $scope.campaign = campaign;
        });
    };

    $scope.addInternalCollaborator = function (collaboratorToAdd) {
        $scope.showAddInternal = false;
        if (!_.isArray($scope.selected.internal_collaborators)) 
            $scope.selected.internal_collaborators = [];

        $scope.selected.all('collaborators').post({ 
            user_id: collaboratorToAdd.id 
        }).then(function () {
            $scope.selected.internal_collaborators.push(collaboratorToAdd);
        });
    };

    $scope.removeInternalCollaborator = function (collab) {
        $scope.selected.one('collaborators', collab.id).remove().then(function () {
            $rootScope.removeRow($scope.selected.internal_collaborators, collab.id);
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

}]);