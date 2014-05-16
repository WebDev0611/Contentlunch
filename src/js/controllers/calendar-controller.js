/* jshint multistr: true */
launch.module.controller('CalendarController',
        ['$scope', '$location', '$timeout', '$modal', '$interpolate', '$http',
function ($scope,   $location,   $timeout,   $modal,   $interpolate,   $http) {
    $scope.title = 'This is the calendar page controller';
    $scope.calendarConfig  = {
        editable: true,
        header:{
            left: 'month agendaWeek agendaDay',
            center: 'prev title next',
            right: 'today'
        },
        dayClick: onDayClick,
        eventDrop: onEventDrop,
        eventResize: onEventResize,
        eventRender: function (event, element, view) {
            element.hide();
            $http.get('/assets/views/calendar/task-node.html', { cache: true }).then(function (response) {
                element.html($interpolate(response.data)(event)).show();
            });

            $http.get('/assets/views/calendar/task-node-popover.html', { cache: true }).then(function (response) {
                element.popover({
                    html: true,
                    content: $interpolate(response.data)(event),
                    placement: 'left',
                    container: 'body',
                    title: $interpolate('<div class="group">\
                                            <div class="calendar-node-popover-title">{{ title }}</div>\
                                            <div class="calendar-node-popover-date">{{ start | date:"shortTime" }}</div>\
                                         </div>')(event)
                });
            });
        }
    };

    // world blows up without this
    $scope.calendarSources = [
        {
            events: [
                {
                    title: 'Banana',
                    description: 'This be the description',
                    connectionIcon: 'blogger.svg',
                    stageIcon: 'edit.png',
                    circleColor: '#9999ff',
                    start: '2014-05-09T11:00:00',
                    // end: '2014-05-09T14:30:00Z',
                    allDay: false // will make the time show
                },
                {
                    title: 'Banana2',
                    description: 'This be the description',
                    connectionIcon: 'blogger2.svg',
                    stageIcon: 'edit.png',
                    circleColor: '#ffcccc',
                    start: '2014-05-16T14:30:00',
                    allDay: false // will make the time show
                }
            ],
            color: '#ba1760',
            textColor: 'whitesmoke'
        }
    ];

    // Events
    // -------------------------
    $scope.newTask = function () {
        $modal.open({
            // set by script template in calendar.html
            templateUrl: 'assets/views/calendar/task-modal.html',
            size: 'lg',
            controller: ['$scope', '$modalInstance',
            function      (scope,    modalInstance) {
                scope.task = {
                    assignees: [{}]
                };

                scope.onOk = function () {
                    modalInstance.close();
                };
                scope.onCancel = function() {
                    modalInstance.dismiss('cancel');
                };
            }]

        });
    };


    // Calendar Functions
    // -------------------------
    function onDayClick() {
        console.log('day click');
    }
    function onEventDrop() {
        console.log('day click');
    }
    function onEventResize() {
        console.log('day click');
    }

    // this really could be handled better :-(
    var autoSetCalendarHeight = function () {
        $scope.calendar.fullCalendar('option', 'height', Math.max($scope.calendar.parent().parent().height() - 100, 450));
    };
    $timeout(autoSetCalendarHeight);
    var debouncedResize = _.debounce(function () {
        $scope.$apply(autoSetCalendarHeight);
    }, 100);
    $(window).on('resize', debouncedResize);
}]);