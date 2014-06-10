/* jshint multistr: true */
launch.module.controller('CalendarController',
        ['$scope', 'AuthService', '$timeout', 'campaignTasks', '$interpolate', '$http', '$q', 'contentStatuses', 'Restangular',
function ($scope,   AuthService,   $timeout,   campaignTasks,   $interpolate,   $http,   $q,   contentStatuses,   Restangular) {
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

    var user = AuthService.userInfo();
    var Account = Restangular.one('account', user.account.id);
    $q.all({
        campaigns: Account.getList('campaigns'),
        content: Account.getList('content'),
        // that's CONTENT tasks to you, boooooiii
        tasks: Account.getList('content-tasks'),
    }).then(function (responses) {
        var tasksByContent = _.groupBy(responses.tasks, 'contentId');

        $scope.calendarSources = [];

        _.each(tasksByContent, function (tasks, contentId) {
            var content = _.findById(responses.content, contentId);
            var color = (content.campaign || {}).color || randomColor();

            var events = _.map(tasks, function (task) {
                return _.merge(task, {
                    title: task.name,
                    description: '', // not used...?
                    contentTypeIconClass: launch.utils.getContentTypeIconClass(content.contentType.key),
                    workflowIconCssClass: launch.utils.getWorkflowIconCssClass(contentStatuses[task.status]),
                    stage: contentStatuses[task.status],
                    circleColor: color,
                    start: task.dueDate,
                    type: 'task',
                    allDay: false, // will make the time show
                    content: content
                });
            });

            $scope.calendarSources.push({
                events: events,
                className: 'calendar-task',
                color: color,
                textColor: 'whitesmoke'
            });
        });

        _.each(responses.campaigns, function (campaign) {
            $scope.calendarSources.push({
                events: [_.merge(campaign, {
                    title: campaign.title,
                    description: '', // not used
                    start: campaign.startDate,
                    end: campaign.endDate,
                    type:'campaign',
                    allDay: true,
                })],
                color: campaign.color,
                textColor: 'whitesmoke'
            });

        })
    });

    function randomColor() {
        return '#'+Math.floor(Math.random()*16777215).toString(16);
    }

    // Events
    // -------------------------
    $scope.newTask = function () {
        // so... we don't actually even show campaign tasks on this page... 
        // so no need to do any more than post, which is handled in the service
        campaignTasks.openModal([], {}, true);
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