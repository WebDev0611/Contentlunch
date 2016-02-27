angular.module('launch').controller('AnnouncementsController',

        ['$scope', '$rootScope', 'Restangular', 'NotificationService', 
function ($scope,   $rootScope,   Restangular,   notify) {
    $scope.isLoaded = false;

    Restangular.all('announcements').getList().then(function (announcements) {
        $scope.announcements = announcements;
        $scope.isLoaded = true;
    }).catch($rootScope.globalErrorHandler);

    $scope.saveAnnouncement = function () {
        if (!$scope.message) {
            notify.error('Please provide a message for the announcement.');
            return;
        }

        $scope.isSaving = true;
        $scope.announcements.post({ message: $scope.message }).then(function (announcement) {
            _.appendOrUpdate($scope.announcements, announcement);
            notify.success('Announcement added.');
            $scope.message = '';
        }).catch($rootScope.globalErrorHandler).then(function () {
            $scope.isSaving = false;
        });
    };

    $scope.deleteAnnouncement = function (announcement) {
        announcement.deleteSpinner = true;

        announcement.remove().then(function () {
            _.remove($scope.announcements, announcement);
            notify.success('Announcement deleted.');
        }).catch($rootScope.globalErrorHandler).then(function () {
            announcement.deleteSpinner = false; 
        });
    };
}]);