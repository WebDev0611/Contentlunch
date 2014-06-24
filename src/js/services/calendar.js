/* jshint multistr: true */
angular.module('launch')

.factory('calendar', 
        ['contentStatuses', '$http', '$interpolate',
function (contentStatuses,   $http,   $interpolate) {
    return {
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
        },

        eventize: function ($scope, contents) {
            var isFirstTime = true;
            return function (campaigns, tasks) {
                var tasksByContent = _.groupBy(tasks, 'contentId');

                $scope.calendarSources = [];

                _.each(tasksByContent, function (tasks, contentId) {
                    var content = _.findById(contents, contentId);
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
                        // events: function (start, end, next) { next(events); },
                        events: events,
                        className: 'calendar-task',
                        color: color,
                        textColor: 'whitesmoke'
                    });    
                    console.log(events[0], events);                    
                });

                _.each(campaigns, function (campaign) {
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

                if (!isFirstTime) {
                    // argh!
                    // update calendar with new event source(s)
                    $('[ui-calendar]').fullCalendar('removeEvents');
                    _.each($scope.calendarSources, function (source) {
                        $('[ui-calendar]').fullCalendar('addEventSource', source);
                    });
                }

                isFirstTime = false;
            };
        }
    };
}]);