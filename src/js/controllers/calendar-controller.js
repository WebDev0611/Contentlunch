﻿/* jshint multistr: true */
launch.module.controller('CalendarController',
        ['$scope', '$location', '$timeout', 'campaignTasks', '$interpolate', '$http',
function ($scope,   $location,   $timeout,   campaignTasks,   $interpolate,   $http) {
    $scope.title = 'This is the calendar page controller';
    $scope.calendarConfig  = {
        editable: false,
        header:{
            left: 'month,agendaWeek',
            center: 'title',
            right: 'prev,next today'
        },
        eventRender: function (event, element, view) {
            if (event.type != 'task') return;
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

    // world blows up without this, but this is all just stubbed data that will go away
    $scope.calendarSources = [
        {
            events: [
                {
                    title: 'Unweildly Long Name That Hopefully is Longer Than Most Names we Encounter',
                    description: 'This be the description',
                    connectionIcon: 'blogger.svg',
                    stageIcon: 'edit.png',
                    circleColor: '#9999ff',
                    start: '2014-05-01T11:00:00',
                    type: 'task',
                    color: 'blue',
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
                    type: 'task',
                    allDay: false // will make the time show
                },
                {
                    title: 'Banana2-2',
                    description: 'This be the description',
                    connectionIcon: 'blogger2.svg',
                    stageIcon: 'edit.png',
                    circleColor: '#ffcccc',
                    start: '2014-05-16T10:30:00',
                    type: 'task',
                    allDay: false // will make the time show
                },
                {
                    title: 'Banana2-3',
                    description: 'This be the description',
                    connectionIcon: 'blogger2.svg',
                    stageIcon: 'edit.png',
                    circleColor: '#ffcccc',
                    start: '2014-05-16T16:30:00',
                    type: 'task',
                    allDay: false // will make the time show
                }
            ],
            className: 'calendar-task',
            color: '#ba1760',
            textColor: 'whitesmoke'
        },
        {
            events: [
                {
                    title: 'Campaign',
                    description: 'This be the description',
                    connectionIcon: 'blogger2.svg',
                    stageIcon: 'edit.png',
                    circleColor: '#ffcccc',
                    start: '2014-05-11',
                    end: '2014-05-13',
                    type:'campaign',
                    allDay: true
                }
            ],
            color: '#afe43f',
            textColor: '#222'
        },
        {
            events: [
                {
                    title: 'Campaign 2',
                    description: 'This be the description',
                    start: '2014-05-01',
                    end: '2014-05-25',
                    type:'campaign',
                    allDay: true
                }
            ],
            color: '#ffb503',
            textColor: '#222'
        }
    ];

    // Events
    // -------------------------
    $scope.newTask = function () {
        campaignTasks.openModal($scope.campaignTasks, {}, true).then(function (tasks) {
            if (tasks) $scope.campaignTasks = tasks;
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
    // this doesn't even really work if we have lots of tasks in the same day
    // var autoSetCalendarHeight = function () {
    //     $scope.calendar.fullCalendar('option', 'height', Math.max($scope.calendar.parent().parent().height() - 100, 450));
    // };
    // $timeout(autoSetCalendarHeight);
    // var debouncedResize = _.debounce(function () {
    //     $scope.$apply(autoSetCalendarHeight);
    // }, 100);
    // $(window).on('resize', debouncedResize);
}]);