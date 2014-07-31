
launch.module.controller('ConsultAdminConferenceController',
        ['$scope', '$filter', '$modal', 'AccountService', 'ConferenceService', 'ModelMapperService', 'NotificationService',
function ($scope,   $filter,   $modal,   AccountService,   ConferenceService,   ModelMapperService,   NotificationService) {
  $scope.conferences = [];

  $scope.conferenceForm = function (conference) {
    var parentScope = $scope;
    $modal.open({
      windowClass: 'modal-large',
      templateUrl: '/assets/views/consult/conference-form.html',
      controller: 
              ['$scope', '$filter', '$modalInstance', 'ConferenceService', 'NotificationService',
      function ($scope,   $filter,   $modalInstance,   ConferenceService,   NotificationService) {
      
              if (conference) {
                $scope.mode = 'edit';
                $scope.conference = conference;
                console.log($scope.conference);
                $scope.conference.account_id = parseInt($scope.conference.account_id);
              } else {
                $scope.mode = 'create';
                $scope.conference = {};
              }
              if ( ! $scope.conference.scheduled_date) {
                var current = new Date();
                current.setMinutes(0);
                $scope.conference.scheduled_date_time = current;
              } else {
                $scope.conference.scheduled_date_time = $scope.conference.scheduled_date;
              }
      
              // Topic options
              $scope.topicOptions = [
                ['marketing', 'Content Marketing'],
                ['strategy', 'Content Strategy'],
                ['application', 'Content Launch Application'],
                ['ideation', 'Content Ideation/Writing'],
                ['publishing', 'Content Publishing'],
                ['promotion', 'Content Promotion']
              ];
      
              // Account options
              $scope.accountOptions = [];
              AccountService.query({
                success: function (response) {
                  angular.forEach(response, function (value) {
                    $scope.accountOptions.push([value.id, value.name ]);
                  });
                }
              });
      
              // Consultant options
              // If topic is application, CEO cannot be selected
              $scope.consultantOptions = function () {
                if ($scope.conference.topic == 'application') {
                  return [['professional', 'Content Launch Professional']];
                }
                return [
                  ['professional', 'Content Launch Professional'],
                  ['ceo', 'CEO Jon Wuebben']
                ];
              };
      
              $scope.cancel = function () {
                $modalInstance.dismiss('cancel');
              };
      
              $scope.ok = function () {
                $scope.conference.scheduled_date = $filter('date')($scope.conference.scheduled_date_date, 'shortDate');
                $scope.conference.scheduled_time = $filter('date')($scope.conference.scheduled_date_time, 'shortTime');
                $scope.conference.scheduled_timezone = moment().format('ZZ');
      
                if ($scope.mode == 'create') {
                  ConferenceService.Conferences.save($scope.conference, function (response) {
                    $modalInstance.dismiss();
                    NotificationService.success('Success', 'Conference added');
                    parentScope.init();
                  }, function (response) {
                    launch.utils.handleAjaxErrorResponse(response, NotificationService);
                  });
                } else {
                  ConferenceService.Conferences.update($scope.conference, function (response) {
                    $modalInstance.dismiss();
                    NotificationService.success('Success', 'Conference updated');
                    parentScope.init();
                  }, function (response) {
                    launch.utils.handleAjaxErrorResponse(response, NotificationService);
                  });
                }
              };
      
              $scope.delete = function () {
                ConferenceService.Conferences.delete($scope.conference, function (response) {
                  $modalInstance.dismiss();
                  NotificationService.success('Success', 'Conference updated');
                  parentScope.init();
                }, function (response) {
                  launch.utils.handleAjaxErrorResponse(response, NotificationService);
                });
              };
      
            }]

    });
  };

  $scope.init = function () {
    ConferenceService.Conferences.query({ account_id: 'all' }, function (response) {
      $scope.conferences = response;
      angular.forEach($scope.conferences, function (value) {
        if (value.date_1) {
          value.date_1 = new Date(moment(value.date_1).format());
        }
        if (value.scheduled_date) {
          value.scheduled_date = value.scheduled_date_date = new Date(moment(value.scheduled_date).format());
        }
        value.launchUser = ModelMapperService.user.fromDto(value.user);
      });
      console.log($scope.conferences);
    }, function (response) {
      launch.utils.handleAjaxErrorResponse(response, NotificationService);
    });
  };
  $scope.init();

}]);