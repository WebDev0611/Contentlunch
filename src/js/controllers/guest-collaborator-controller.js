angular.module('launch')
.controller('GuestCollaboratorController', 
        ['$scope', '$rootScope', '$location', 'Restangular', '$q', 'AuthService', '$filter', '$routeParams', '$modal', 'guestCollaborators', 'NotificationService', 
function ($scope,   $rootScope,   $location,   Restangular,   $q,   AuthService,   $filter,   $routeParams,   $modal,   guestCollaborators,   notify) {
    Restangular.one('guest-collaborators', $routeParams.accessCode).get().then(function (guest) {
        $scope.guest = guest;
    });
}]);