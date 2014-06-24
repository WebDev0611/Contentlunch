launch.module.controller('CalendarController',
        ['$scope', 'AuthService', '$timeout', 'campaignTasks', '$q', 'calendar', 'Restangular',
function ($scope,   AuthService,   $timeout,   campaignTasks,   $q,   calendar,   Restangular) {

    // different permissions
    // calendar_execute_campaigns_own
    // calendar_view_campaigns_other
    // calendar_edit_campaigns_other
    // calendar_execute_schedule
    // calendar_view_archive
    // calendar_execute_archive
    // calendar_execute_export
    $scope.calendar = {};

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
        eventRender: calendar.eventRender
    };

    var Account = Restangular.one('account', user.account.id);
    var originalResponses = {};
    $scope.isLoaded = false;
    var eventize;
    $q.all({
        campaigns: Account.getList('campaigns'),
        content: Account.getList('content'),
        // that's CONTENT tasks to you, boooooiii
        tasks: Account.getList('content-tasks'),
        users: Account.getList('users'),
    }).then(function (responses) {
        originalResponses = _.mapObject(responses, function (response, key) {
            return [key, response.plain ? response.plain() : response];
        });

        var contentObj = _.mapObject(originalResponses.content, function (content) {
            return [content.id, content];
        });
        originalResponses.tasks = _.map(originalResponses.tasks, function (task) {
            task.content = contentObj[task.contentId];
            return task;
        });

        angular.extend($scope, angular.copy(originalResponses));

        eventize = calendar.eventize($scope, responses.content);
        eventize(responses.campaigns, responses.tasks);

        $scope.isLoaded = true;
    });

    function randomColor() {
        return '#' + Math.floor(Math.random() * 16777215).toString(16);
    }

    // Actions
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

    $scope.clearFilters = function () {
        $scope.filters = {};
    };

    $scope.saveFilters = function (filters) {
        console.error('save filter NYI');
    };

    $scope.$watch('filters', function (filters) {
        $scope.campaigns = filterItems(originalResponses.campaigns);
        $scope.tasks     = filterItems(originalResponses.tasks);

        if (eventize) {
            eventize($scope.campaigns, $scope.tasks);
        }
    }, true);

    // :searchTerm

    $scope.filters = {};
    var searches = {
        contentTypes: 'contentTypeId',
        milestones: 'milestoneId',
        buyingStages: 'buyingStageId',
        campaigns: 'campaignId',
        users: 'userId'
    };
    function filterItems(items) {
        return _.reduce(searches, function (filtered, modelKey, filterKey) {
            var array = $scope.filters[filterKey];
            if (_.isEmpty(array)) return filtered;
            return _.filter(filtered, containFilter(modelKey, array));
        }, items);               
    }

    function containFilter(prop, array) {
        return function (item) {
            if (!item.hasOwnProperty(prop) && !(item.content || {}).hasOwnProperty(prop)) return true;
            return (item.content && _.contains(array, item.content[prop])) || _.contains(array, item[prop]);
        };
    }


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