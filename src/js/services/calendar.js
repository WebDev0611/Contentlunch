/* jshint multistr: true */
angular.module('launch')

.factory('calendar', 
        ['contentStatuses', '$http', '$interpolate', '$compile', '$rootScope',
function (contentStatuses,   $http,   $interpolate,   $compile,   $rootScope) {
    var $elem, calendar, currentEvents;
    $(document).on('click', '.popover-close', function () {
        $(this).closest('.popover').popover('hide');
    });
    return (calendar = {
        // I know this should be a directive, but I was using ui-calendar and seeing
        // MASSIVE performance issues and it was easiest just to change it to this
        init: function (config) {
            $elem = $('#js-cl-calendar');
            $elem.fullCalendar(config);
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
                                                <div class="popover-close">&times;</div>\
                                                <div class="calendar-node-popover-title">{{ title }}</div>\
                                                <div class="calendar-node-popover-date">{{ start | date:"shortTime" }}</div>\
                                             </div>')(event)
                    });
                });
            } else if (event.type == 'campaign') {
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
                                                <div class="popover-close">&times;</div>\
                                                <div class="calendar-node-popover-title-2"><a href="/calendar/campaigns/{{ id }}">{{ title }}</a></div>\
                                                <div class="calendar-node-popover-date-2">{{ start | date:"mediumDate" }} - {{ end | date:"mediumDate" }}</div>\
                                             </div>')(event)
                    });
                });
            } else if (event.type == 'brainstorm') {
                element.hide();

                // this re-styles the tasks items on the 
                // calendar to match the spec
                $http.get('/assets/views/calendar/brainstorm-node.html', { cache: true }).then(function (response) {
                    element.html($interpolate(response.data)(event)).show();
                });

                // this is how we attached a click event to
                // the task items in the calendar using
                // BS popover and an external template
                var _scope = $rootScope.$new(true);
                angular.extend(_scope, event);
                $http.get('/assets/views/calendar/brainstorm-node-popover.html', { cache: true }).then(function (response) {
                    element.popover({
                        html: true,
                        content: $compile(response.data)(_scope),
                        placement: 'left',
                        container: 'body',
                        title: $interpolate('<div class="group">\
                                                <div class="popover-close">&times;</div>\
                                                <div class="calendar-node-popover-title">{{ title }}</div>\
                                                <div class="calendar-node-popover-date">{{ start | date:"shortTime" }}</div>\
                                             </div>')(event)
                    });
                });
            } else {
                console.error('Unrecognized event type: ' + event.type);
            }
        },

        eventize: function (contents) {
            var isFirstTime = true;
            return function (campaigns, tasks, brainstorms) {
                var tasksByContent = _.groupBy(tasks, 'contentId');

                var events = [];

                _.each(tasksByContent, function (tasks, contentId) {
                    var content = _.findById(contents, contentId);
                    if (!content) content = {};
                    var color = (content.campaign || {}).color || randomColor();

                    _.each(tasks, function (task) {
                        task = _.merge(task, {
                            uniqId: 'task_' + task.id,
                            title: task.name,
                            contentTypeIconClass: launch.utils.getContentTypeIconClass((content.contentType || {}).key),
                            workflowIconCssClass: launch.utils.getWorkflowIconCssClass(contentStatuses[task.status]),
                            stage: contentStatuses[task.status],
                            circleColor: color,
                            start: task.dueDate,
                            type: 'task',
                            allDay: false, // will make the time show
                            content: content,
                            sourceOpts: {
                                className: 'calendar-task',
                                color: color,
                                textColor: 'whitesmoke'
                            }
                        });

                        events.push(task);
                    });
                });

                _.each(campaigns, function (campaign) {
                    events.push(_.merge(campaign, {
                        uniqId: 'campaign_' + campaign.id,
                        title: campaign.title,
                        start: campaign.startDate,
                        end: campaign.endDate,
                        type:'campaign',
                        allDay: true,
                        sourceOpts: {
                            color: campaign.color,
                            textColor: 'whitesmoke'
                        }
                    }));
                });

                _.each(brainstorms, function (brainstorm) {
                    if (!brainstorm.campaign) {
                        if (brainstorm.campaignId) 
                            brainstorm.campaign = _.findById(campaigns, brainstorm.campaignId) || {};
                        else
                            brainstorm.campaign = {};
                    }
                    if (!brainstorm.content) {
                        if (brainstorm.contentId) 
                            brainstorm.content = _.findById(contents, brainstorm.contentId) || {};
                        else
                            brainstorm.content = {};
                    }

                    events.push(_.merge(brainstorm, {
                        uniqId: 'brainstorm_' + brainstorm.id,
                        title: 'Brainstorming Session',
                        start: brainstorm.date + 'T' + brainstorm.time,
                        type:'brainstorm',
                        sourceOpts: {
                            className: 'calendar-task',
                            color: randomColor(),
                            textColor: 'whitesmoke'
                        }
                    }));
                });

                var newEvents = events;

                var eventsToRemove = toRemove(newEvents, currentEvents);
                $elem.fullCalendar('removeEvents', function (event) {
                    return eventsToRemove[event.uniqId];
                });

                var sourcesToAdd = toAdd(newEvents, currentEvents);
                _.each(sourcesToAdd, function (source) {
                    $elem.fullCalendar('addEventSource', source);
                });

                currentEvents = newEvents;
                isFirstTime = false;
            };
        }
    });

    function randomColor() {
        return '#' + Math.floor(Math.random() * 16777215).toString(16);
    }

    function toRemove(newEvents, currentEvents) {
        var difference = _.difference(_.pluck(currentEvents, 'uniqId'), _.pluck(newEvents, 'uniqId'));
        return _.object(difference, difference);
    }

    function toAdd(newEvents, currentEvents) {
        var difference = _.difference(_.pluck(newEvents, 'uniqId'), _.pluck(currentEvents, 'uniqId'));
        return _.map(difference, function (uniqId) {
            var event = _.find(newEvents, { uniqId: uniqId });
            var source = angular.copy(event.sourceOpts);
            source.events = [event];
            return source;
        });
    }
}]);