launch.module.controller('CampaignController',
        ['$scope', 'AuthService', '$routeParams', '$modal', '$q', 'Restangular',
function ($scope,   AuthService,   $routeParams,   $modal,   $q,   Restangular) {
    var user     = AuthService.userInfo();
    var Account  = Restangular.one('account', user.account.id);
    var Campaign = Account.all('campaigns');

    var campaignPromise = $routeParams.campaignId === 'new' ? { isNew: true } : Campaign.get($routeParams.campaignId);

    $q.all({
        campaign: campaignPromise,
        campaignTypes: Restangular.all('campaign-types').getList()
    }).then(function (responses) {
        angular.extend($scope, responses);
    });

    $scope.saveCampaign = function (campaign) {
        (campaign.isNew ? Campaign.post(campaign) : campaign.put()).then(function (campaign) {
            $scope.campaign = campaign;
        });
    };
}]);