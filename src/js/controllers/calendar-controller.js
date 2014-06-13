/* jshint multistr: true */
launch.module.controller('CalendarController',
        ['$scope', 'AuthService', '$timeout', 'campaignTasks', '$interpolate', '$http', '$q', 'contentStatuses', 'Restangular',
function ($scope,   AuthService,   $timeout,   campaignTasks,   $interpolate,   $http,   $q,   contentStatuses,   Restangular) {

    // different permissions
    // calendar_execute_campaigns_own
    // calendar_view_campaigns_other
    // calendar_edit_campaigns_other
    // calendar_execute_schedule
    // calendar_view_archive
    // calendar_execute_archive
    // calendar_execute_export

    var user = $scope.user = AuthService.userInfo();
    $scope.canCreate = user.hasPrivilege('calendar_execute_campaigns_own');
    $scope.canExport = user.hasPrivilege('calendar_execute_export');
    // $scope.canCreateTask = user.hasPrivilege('calendar_execute_schedule');

    $scope.calendarConfig  = {
        editable: false,
        header:{
            left: 'month,agendaWeek',
            center: 'title',
            right: 'prev,next today'
        },
        // @note that in all of these $interpolate functions,
        // the it will render stuff in {{ }}, but it is NOT a
        // full $compile and won't render stuff like ngRepeat
        eventRender: function (event, element, view) {
            if (event.type == 'task') {
                element.hide();

                // this re-styles the tasks items on the 
                // calendar to match the spec
                $http.get('/assets/views/calendar/task-node.html', { cache: true }).then(function (response) {
                    element.html($interpolate(response.data)(event)).show();
                });

                // this is how we attached a click event to
                // the task items in the calendar using
                // BS popover and an external template
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
            } else { // (event.type != 'task')
                // this is how we attached a click event to
                // the campaign items in the calendar using
                // BS popover and an external template
                $http.get('/assets/views/calendar/campaign-node-popover.html', { cache: true }).then(function (response) {
                    element.popover({
                        html: true,
                        content: $interpolate(response.data)(event),
                        placement: 'left',
                        container: 'body',
                        title: $interpolate('<div class="group">\
                                                <div class="calendar-node-popover-title-2"><a href="/calendar/campaigns/{{ id }}">{{ title }}</a></div>\
                                                <div class="calendar-node-popover-date-2">{{ start | date:"mediumDate" }} - {{ end | date:"mediumDate" }}</div>\
                                             </div>')(event)
                    });
                });
            }
        }
    };

    var Account = Restangular.one('account', user.account.id);
    $q.all({
        campaigns: Account.getList('campaigns'),
        content: Account.getList('content'),
        // that's CONTENT tasks to you, boooooiii
        tasks: Account.getList('content-tasks'),
    }).then(function (responses) {
        var tasksByContent = _.groupBy(responses.tasks, 'contentId');

        $scope.campaigns = responses.campaigns;
        $scope.calendarSources = [];

        _.each(tasksByContent, function (tasks, contentId) {
            var content = _.findById(responses.content, contentId);
            var color = (content.campaign || {}).color || randomColor();

            var events = _.map(tasks, function (task) {
                return _.merge(task, {
                    title: task.name,
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
                    start: campaign.startDate,
                    end: campaign.endDate,
                    type:'campaign',
                    allDay: true,
                })],
                color: campaign.color,
                textColor: 'whitesmoke'
            });

        });
    });

    function randomColor() {
        return '#' + Math.floor(Math.random() * 16777215).toString(16);
    }

    // Events
    // -------------------------
    $scope.newTask = function () {
        // so... we don't actually even show campaign tasks on this page... 
        // so no need to do any more than post, which is handled in the service
        campaignTasks.openModal([], {}, true);
    };


    // Helpers
    // -------------------------
    $scope.pagination = {
        pageSize: 10,
        currentPage: 1,
    };


    // Calendar Functions
    // -------------------------
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