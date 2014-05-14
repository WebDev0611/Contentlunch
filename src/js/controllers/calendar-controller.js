launch.module.controller('CalendarController',
        ['$scope', '$location', '$timeout', '$modal',
function ($scope,   $location,   $timeout,   $modal) {
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
        eventResize: onEventResize
    };

    var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();

    // world blows up without this
    $scope.calendarSources = [
        {
            events: [
                {
                    title  : 'Banana',
                    start  : '2014-05-09T12:30:00Z',
                    end    : '2014-05-09T14:30:00Z',
                    allDay : false // will make the time show
                }
            ],
            color: '#333',
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