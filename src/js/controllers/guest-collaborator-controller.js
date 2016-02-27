angular.module('launch')
.controller('GuestCollaboratorController', 
        ['$scope', '$rootScope', '$location', 'Restangular', '$q', 'AuthService', '$filter', '$stateParams', '$modal', 'guestCollaborators', 'NotificationService',
function ($scope,   $rootScope,   $location,   Restangular,   $q,   AuthService,   $filter,   $stateParams,   $modal,   guestCollaborators,   notify) {
    Restangular.one('guest-collaborators', $stateParams.accessCode).get().then(function (guest) {
        $scope.guest = guest;
    });
}]);