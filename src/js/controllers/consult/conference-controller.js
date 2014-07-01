
launch.module.controller('ConsultConferenceController', function ($scope, $filter, AuthService, ConferenceService, NotificationService) {

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
          value.scheduled_date = new Date(value.scheduled_date);
        }
      });
    });
  };
  $scope.init();

});