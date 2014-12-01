/* jshint multistr: true */
angular.module('launch')

.factory('calendar', 
        ['contentStatuses', '$http', '$interpolate', '$compile', '$rootScope',
function (contentStatuses,   $http,   $interpolate,   $compile,   $rootScope) {
    var $elem, calendar, currentEvents = [];
    $(document).on('click', '.popover-close', function () {
        $(this).closest('.popover').popover('hide');
    });
    return (calendar = {
        // I know this should be a directive, but I was using ui-calendar and seeing
        // MASSIVE performance issues and it was easiest just to change it to this
    	init: function (config) {
		    currentEvents = [];
            $elem = $('#js-cl-calendar');
            $elem.fullCalendar(config);
        },

        refresh: function() {
            $('#js-cl-calendar').fullCalendar('refetchEvents');
        },

        addEvent: function(event) {
            $('#js-cl-calendar').fullCalendar( 'renderEvent', event );
        },

        // @note that in all of these $interpolate functions,
        // the it will render stuff in {{ }}, but it is NOT a
        // full $compile and won't render stuff like ngRepeat
        eventRender: function (event, element, view) {
            if (event.type == 'content_task') {
                element.hide();

                // this re-styles the tasks items on the 
                // calendar to match the spec
                $http.get('/assets/views/calendar/content-task-node.html', { cache: true }).then(function (response) {
                    element.html($interpolate(response.data)(event)).show();
                });

                // this is how we attached a click event to
                // the task items in the calendar using
                // BS popover and an external template
                $http.get('/assets/views/calendar/content-task-node-popover.html', { cache: true }).then(function (response) {
                    element.popover({
                        html: true,
                        content: $interpolate(response.data)(event),
                        placement: 'left',
                        container: 'body',
                        title: $interpolate('<div class="group">\
                                                <div class="popover-close">&times;</div>\
                                                <div class="calendar-node-popover-title">{{ title }}</div>\
                                                <div class="calendar-node-popover-date">{{ start.format() | date:"MM/dd/yyyy" }}</div>\
                                             </div>')(event)
                    });
                });
            }else if (event.type == 'campaign_task') {
                element.hide();

                // this re-styles the tasks items on the
                // calendar to match the spec
                $http.get('/assets/views/calendar/campaign-task-node.html', { cache: true }).then(function (response) {
                    element.html($interpolate(response.data)(event)).show();
                });

                // this is how we attached a click event to
                // the task items in the calendar using
                // BS popover and an external template
                $http.get('/assets/views/calendar/campaign-task-node-popover.html', { cache: true }).then(function (response) {
                    element.popover({
                        html: true,
                        content: $interpolate(response.data)(event),
                        placement: 'left',
                        container: 'body',
                        title: $interpolate('<div class="group">\
                                                <div class="popover-close">&times;</div>\
                                                <div class="calendar-node-popover-title">{{ title }}</div>\
                                                <div class="calendar-node-popover-date">{{ start.format() | date:"MM/dd/yyyy" }}</div>\
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
                                                <div class="calendar-node-popover-date-2">{{ start.format() | date:"mediumDate" }} - {{ end.format() | date:"mediumDate" }}</div>\
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
                                                <div class="calendar-node-popover-date">{{ start.format() | date:"shortTime" }}</div>\
                                             </div>')(event)
                    });
                });
            } else {
                console.error('Unrecognized event type: ' + event.type);
            }
        },
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