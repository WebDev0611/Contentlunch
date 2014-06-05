launch.module.controller('CampaignController',
        ['$scope', 'AuthService', '$routeParams', '$modal', '$q', 'Restangular',
function ($scope,   AuthService,   $routeParams,   $modal,   $q,   Restangular) {
    var user     = AuthService.userInfo();
    var Account  = Restangular.one('account', user.account.id);
    var Campaign = Account.all('campaigns');

    if ($routeParams.campaignId === 'new') {
        $scope.campaign = { isNew: true };
    } else {
        Campaign.get($routeParams.campaignId).then(function (campaign) {
            $scope.campaign = campaign;
        });
    }

    $scope.saveCampaign = function (campaign) {
        (campaign.isNew ? Campaign.post(campaign) : campaign.put()).then(function (campaign) {
            $scope.campaign = campaign;
        });
    };
}]);