
launch.module.controller('ConsultConferenceController', function ($scope, $filter, $modal, AuthService, ConferenceService, ModelMapperService, NotificationService) {

  $scope.user = AuthService.userInfo();

  // Topic options
  $scope.topicOptions = [
    ['marketing', 'Content Marketing'],
    ['strategy', 'Content Strategy'],
    ['application', 'Content Launch Application'],
    ['ideation', 'Content Ideation/Writing'],
    ['publishing', 'Content Publishing'],
    ['promotion', 'Content Promotion']
  ];

  // Consultant options
  // If topic is application, CEO cannot be selected
  $scope.consultantOptions = function () {
    if ($scope.request.topic == 'application') {
      return [['professional', 'Content Launch Professional']];
    }
    return [
      ['professional', 'Content Launch Professional'],
      ['ceo', 'CEO Jon Wuebben']
    ];
  };

  $scope.sendRequest = function () {
    $scope.request.account_id = $scope.user.account.id;
    $scope.request.user_id = $scope.user.id;
    $scope.request.time_1 = $filter('date')($scope.request.time1, 'shortTime');
    $scope.request.time_2 = $filter('date')($scope.request.time2, 'shortTime');
    ConferenceService.Conferences.save($scope.request, function (response) {
      NotificationService.success('Success', 'Your request has been sent');
      $scope.init();
    }, function (response) {
      launch.utils.handleAjaxErrorResponse(response, NotificationService);
    });
  };

  $scope.conferences = [];

  $scope.conferenceDetail = function (conference) {
    var parentScope = $scope;
    $modal.open({
      windowClass: 'modal-large',
      templateUrl: '/assets/views/consult/conference-form.html',
      controller: function ($scope, $filter, $modalInstance, ConferenceService, NotificationService) {

        $scope.mode = 'view';
        $scope.conference = conference;
        $scope.conference.account_id = parseInt($scope.conference.account_id);
        
        if ( ! $scope.conference.scheduled_date) {
        
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

        $scope.users = [
          ModelMapperService.user.fromDto($scope.conference.user)
        ];

        $scope.close = function () {
          $modalInstance.dismiss('cancel');
        };

      }

    });
  };

  // Schedule date requests must be > 3 days and < 2 weeks
  $scope.minDate = moment().add('days', 4);
  $scope.maxDate = moment().add('days', 13);

  $scope.init = function () {
    var current = new Date();
    current.setMinutes(0);
    $scope.request = {
      time1: current,
      time2: current,
      topic: 'marketing',
      consultant: 'professional'
    };
    ConferenceService.Conferences.query({ account_id: $scope.user.account.id }, function (response) {
      $scope.conferences = response;
      angular.forEach($scope.conferences, function (value) {
        if (value.scheduled_date) {
          value.scheduled_date = new Date(moment(value.scheduled_date).format());
        }
      });
    });
  };
  $scope.init();

});