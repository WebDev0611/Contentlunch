launch.module.controller('CalendarController',
        ['$scope', 'AuthService', '$timeout', '$filter', 'UserService', 'campaignTasks', '$q', 'contentStatuses', 'calendar', 'Restangular', 'NotificationService',
function ($scope,   AuthService,   $timeout,   $filter,   UserService,   campaignTasks,   $q,   contentStatuses,   calendar,   Restangular,   notify) {

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

    var calendarConfig  = {
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
        brainstorms: Account.getList('brainstorm'),
        // that's CONTENT tasks to you, boooooiii
        tasks: Account.getList('content-tasks'),
        users: Account.getList('users'),
        contentSettings: Account.customGET('content-settings'),
        contentTypes: Restangular.all('content-types').getList(),
        userAuth: Restangular.all('auth').customGET(),
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
        originalResponses.campaigns = _.map(originalResponses.campaigns, function (campaign) {
            campaign.campaignId = campaign.id;
            return campaign;
        });

        angular.extend($scope, angular.copy(originalResponses));
        $scope.buyingStages = _.map(originalResponses.contentSettings.personaColumns, function (col, i) {
            return {
                // content.buyingStage is a string, so we need this 
                // to be a string also for this filter to work
                id: i + '',
                name: launch.utils.titleCase(col)
            };
        });
        $scope.milestones = _.map(contentStatuses, function (status, i) {
            return {
                id: i,
                name: launch.utils.titleCase(status)
            };
        });

        // using campaignList so that we always have them all in the dropdown
        $scope.campaignList = angular.copy($scope.campaigns);

        $scope.isLoaded = true;

        $scope.filters = ((originalResponses.userAuth || {}).preferences || {}).calendar || {};

        $timeout(function () {
            calendar.init(calendarConfig);
            eventize = calendar.eventize($scope, responses.content);
            eventize(responses.campaigns, responses.tasks, responses.brainstorms);
        });
    });

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
        $scope.filters = { onlyMine: true };
    };

    $scope.saveFilters = function (filters) {
        UserService.savePreferences(user.id, 'calendar', filters, {
            success: function () {
                notify.success('Success', 'Calendar default filters saved.');
            }
        });
    };

    var filterDebouncer = _.debounce(function (filters) {
        $scope.campaigns = filterItems(originalResponses.campaigns);
        var tasks = originalResponses.tasks;

        // first filter for "my" tasks if needed
        if ($scope.filters.onlyMine) {
            tasks = _.filter(tasks, function (task) {
                return task.userId == user.id;
            });
        }

        $scope.tasks = filterItems(tasks);

        $scope.brainstorms = filterItems(originalResponses.brainstorms);

        if (eventize)
            eventize($scope.campaigns, $scope.tasks, $scope.brainstorms);
    }, 300);
    $scope.$watch('filters', filterDebouncer, true);

    // :searchTerm

    $scope.filters = { onlyMine: true };
    var searches = {
        contentTypes: 'contentTypeId',
        milestones: 'milestoneId',
        buyingStages: 'buyingStage',
        campaigns: 'campaignId',
        users: 'userId'
    };
    function filterItems(items) {
        items = _.reduce(searches, function (filtered, modelKey, filterKey) {
            var array = $scope.filters[filterKey];
            if (_.isEmpty(array)) return filtered;
            return _.filter(filtered, containFilter(modelKey, array));
        }, items);

        var searchTerm = $.trim(($scope.filters.searchTerm || '').toLowerCase());
        if (!searchTerm) return items;

        return $filter('filter')(items, function (value) {
            var inResult = false;
            if (value.name)  inResult = inResult || _.contains(value.name.toLowerCase(),  searchTerm);
            if (value.title) inResult = inResult || _.contains(value.title.toLowerCase(), searchTerm);
            if (value.tags)  inResult = inResult || _.any(value.tags, function(tag) { return _.contains((tag.tag || tag || '').toLowerCase(), searchTerm); });
            if ((value.content || {}).tags) inResult = inResult || _.any(value.content.tags, function(tag) { return _.contains((tag.tag || tag || '').toLowerCase(), searchTerm); });

            return inResult;
        });
    }

    function containFilter(prop, array) {
        return function (item) {
            // if we don't have that property for some reason, skip testing it
            if (!item.hasOwnProperty(prop) && !(item.content || {}).hasOwnProperty(prop)) return true;

            // return if an item (or item's content) has that property in the selected stuff
            return (item.content && _.contains(array, item.content[prop])) || _.contains(array, item[prop]);
        };
    }

    $scope.formatContentTypeItem = function(item, element, context) {
        return '<span class="' + launch.utils.getContentTypeIconClass(item.text) + '"></span> <span>' + item.text + '</span>';
    };
    $scope.formatCampaignItem    = launch.utils.formatCampaignItem;
    $scope.formatBuyingStageItem = launch.utils.formatBuyingStageItem;
    $scope.formatMilestoneItem   = function(item, element, context) {
        return '<span class="' + launch.utils.getWorkflowIconCssClass(item.text) + '"></span> <span>' + item.text + '</span>';
    };
    $scope.formatUserItem = function (item, element, context) {
        if (!item.text) return element.attr('placeholder');
        var user = _.findById($scope.users, item.id)[0] || {};
        var style = ' style="background-image: url(\'' + $filter('imagePathFromObject')(user.image) + '\')"';

        return '<span class="user-image user-image-small"' + style + '></span> <span>' + item.text + '</span>';
    };
}]);