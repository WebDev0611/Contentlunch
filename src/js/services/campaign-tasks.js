angular.module('launch')

.factory('campaignTasks', [
         '$modal', 'Restangular', 'AuthService',
function ($modal,   Restangular,   AuthService) {
    var user = AuthService.userInfo();
    var Account = Restangular.one('account', user.account.id);

    return {
        openModal: function (tasks, task, showCampaignPicker) {
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
                    $scope.task = task ? Restangular.copy(task) : {};
                }]
            }).result.then(function (task) {
                var Tasks = tasks.post ? tasks : Account.one('campaigns', task.campaignId).all('tasks');
                return (task.id ? task.put() : Tasks.post(task)).then(function (task) {
                    _.appendOrUpdate(tasks, task);
                    return tasks;
                });
            }, angular.noop);
        }
    };
}]);