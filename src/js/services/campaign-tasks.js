angular.module('launch')

.factory('campaignTasks', [
         '$modal', 'Restangular', 'AuthService',
function ($modal,   Restangular,   AuthService) {
    var user = AuthService.userInfo();
    var Account = Restangular.one('account', user.account.id);

    return {
        openModal: function (tasks, showCampaignPicker) {
            showCampaignPicker = !!showCampaignPicker;

            return $modal.open({
                templateUrl: '/assets/views/calendar/task-modal.html',
                size: 'lg',
                resolve: {
                    // campaigns: function () { return Account.withHttpConfig({ cache: true }).getList('campaigns'); }
                    campaigns: function () { return Account.getList('campaigns'); },
                    users: function () { return Account.getList('users'); }
                },
                controller: ['$scope', '$modalInstance', 'campaigns', 'users',
                function     ($scope,   $modalInstance,   campaigns,   users) {
                    $scope.showCampaignPicker = showCampaignPicker;
                    $scope.campaigns = campaigns;
                    $scope.users = users;
                    $scope.task = {};
                }]
            }).result.then(function (task) {
                return (task.id ? task.put() : tasks.post(task)).then(function (task) {
                    _.appendOrUpdate(tasks, task);
                    return tasks;
                });
            }, angular.noop);
        }
    };
}]);