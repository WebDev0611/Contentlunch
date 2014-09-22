launch.module.controller('CalendarController',
[
	'$scope', 'AuthService', '$timeout', '$filter', 'UserService', 'campaignTasks', '$q', 'contentStatuses', 'calendar', 'Restangular', 'NotificationService',
	function($scope, AuthService, $timeout, $filter, UserService, campaignTasks, $q, contentStatuses, calendar, Restangular, notify) {

		// different permissions
		// calendar_execute_campaigns_own
		// calendar_view_campaigns_other
		// calendar_edit_campaigns_other
		// calendar_execute_schedule
		// calendar_view_archive
		// calendar_execute_archive
		// calendar_execute_export

		$scope.calendar = { };

		var user = $scope.user = AuthService.userInfo();
		$scope.canCreate = user.hasPrivilege('calendar_execute_campaigns_own');
		$scope.canExport = user.hasPrivilege('calendar_execute_export');
		// $scope.canCreateTask = user.hasPrivilege('calendar_execute_schedule');

		$scope.sortOrder = 'title';

        function randomColor() {
            return '#' + Math.floor(Math.random() * 16777215).toString(16);
        }

        var contentTasksCache = {};
        var campaignTasksCache = {};
        var campaignCache = {};
        var brainstormCache = {};

		var calendarConfig = {
			editable: false,
			header: {
				left: 'month,agendaWeek',
				center: 'title',
				right: 'prev,next today'
			},
            slotEventOverlap: false,
			eventRender: calendar.eventRender,
            eventSources: [
                function(start, end, timezone, callback) {
                    var makeEvents = function(tasks) {
                        return _.map(tasks, function(task) {
                            var content = task.taskGroup.content
                            var color = (content.campaign || { }).color || randomColor();

                            return _.merge(task, {
                                uniqId: 'content_task_' + task.id,
                                title: task.name,
                                contentTypeIconClass: launch.utils.getContentTypeIconClass((content.contentType || { }).key),
                                workflowIconCssClass: launch.utils.getWorkflowIconCssClass(contentStatuses[task.stepId]),
                                stage: contentStatuses[task.status],
                                circleColor: color,
                                start: task.dueDate,
                                type: 'content_task',
                                allDay: false, // will make the time show
                                content: content,
                                className: 'calendar-task',
                                color: color,
                                textColor: 'whitesmoke'
                            });
                        })
                    };

                    var key = start.unix()+'_'+end.unix();
                    if(typeof(contentTasksCache[key]) !== 'undefined') {
                        var response = contentTasksCache[key];
                        var filtered = filterItems(response);
                        callback(makeEvents(filtered));
                        return;
                    }

                    Account.getList('content-tasks', {start: start.format('YYYY-MM-DD'), end: end.format('YYYY-MM-DD')})
                        .then(function(response) {
                            _.each(response, function(task) {
                                task.type = 'content_task';
                                task.stepId = task.taskGroup.status;
                                task.isComplete = parseInt(task.isComplete);
                            });
                            contentTasksCache[key] = response;
                            var filtered = filterItems(response);

                            var events = makeEvents(filtered);

                            console.log(events);
                            callback(events);
                        });
                },

                function(start, end, timezone, callback) {
                    var makeEvents = function(tasks) {
                        return _.map(tasks, function(task) {
                            var campaign = task.campaign
                            var color = (campaign || { }).color || randomColor();

                            return _.merge(task, {
                                uniqId: 'campaign_task_' + task.id,
                                title: task.name,
                                circleColor: color,
                                start: task.dueDate,
                                type: 'campaign_task',
                                allDay: false, // will make the time show
                                campaign: campaign,
                                className: 'calendar-task',
                                color: color,
                                textColor: 'whitesmoke'
                            });
                        })
                    };

                    var key = start.unix()+'_'+end.unix();
                    if(typeof(campaignTasksCache[key]) !== 'undefined') {
                        var response = campaignTasksCache[key];
                        var filtered = filterItems(response);
                        callback(makeEvents(filtered));
                        return;
                    }

                    Account.getList('campaign-tasks', {start: start.format('YYYY-MM-DD'), end: end.format('YYYY-MM-DD')})
                        .then(function(response) {
                            _.each(response, function(task) {
                                task.type = 'campaign_task';
                                task.isComplete = parseInt(task.isComplete);
                            });
                            campaignTasksCache[key] = response;
                            var filtered = filterItems(response);

                            var events = makeEvents(filtered);

                            console.log(events);
                            callback(events);
                        });
                },

                function(start, end, timezone, callback) {
                    var makeEvents = function(campaigns) {
                        return _.map(campaigns, function(campaign) {
                            return _.merge(campaign, {
                                uniqId: 'campaign_' + campaign.id,
                                title: campaign.title,
                                start: campaign.startDate,
                                end: campaign.endDate,
                                type: 'campaign',
                                allDay: true,
                                color: campaign.color,
                                textColor: 'whitesmoke'
                            });
                        })
                    };

                    var key = start.unix()+'_'+end.unix();
                    if(typeof(campaignCache[key]) !== 'undefined') {
                        var response = campaignCache[key];
                        var filtered = filterItems(response);
                        callback(makeEvents(filtered));
                        return;
                    }

                    Account.getList('campaigns', {start: start.format('YYYY-MM-DD'), end: end.format('YYYY-MM-DD')})
                        .then(function(response) {
                            _.each(response, function(campaign) {
                                campaign.campaignId = campaign.id;
                                campaign.type = 'campaign';
                            });
                            campaignCache[key] = response;
                            var filtered = filterItems(response);

                            var events = makeEvents(filtered);

                            console.log(events);
                            callback(events);
                        });
                },

                function(start, end, timezone, callback) {
                    var makeEvents = function(brainstorms) {
                        return _.map(brainstorms, function(brainstorm) {
                            return _.merge(brainstorm, {
                                uniqId: 'brainstorm_' + brainstorm.id,
                                title: 'Brainstorming Session',
                                start: brainstorm.datetime,
                                type: 'brainstorm',
                                className: 'calendar-task',
                                color: randomColor(),
                                textColor: 'whitesmoke'
                            });
                        })
                    };

                    var key = start.unix()+'_'+end.unix();
                    if(typeof(brainstormCache[key]) !== 'undefined') {
                        var response = brainstormCache[key];
                        var filtered = filterItems(response);
                        callback(makeEvents(filtered));
                        return;
                    }

                    Account.getList('brainstorm-calendar', {start: start.format('YYYY-MM-DD'), end: end.format('YYYY-MM-DD')})
                        .then(function(response) {
                            _.each(response, function(brainstorm) {
                                brainstorm.type = 'brainstorm';
                                brainstorm.datetime = moment.utc(brainstorm.datetime)
                            });
                            brainstormCache[key] = response;
                            var filtered = filterItems(response);

                            var events = makeEvents(filtered);

                            console.log(events);
                            callback(events);
                        });
                }
            ]
		};

		var Account = Restangular.one('account', user.account.id);
		var originalResponses = { };
		$scope.isLoaded = false;
		var eventize;
		$q.all({
			campaigns: Account.getList('campaigns'),
			content: Account.getList('content'),
			brainstorms: Account.getList('brainstorm'),
			// that's CONTENT tasks to you, boooooiii
            tasks: Account.getList('content-tasks'),
            campaign_tasks: Account.getList('campaign-tasks'),
			users: Account.getList('users'),
			contentSettings: Account.customGET('content-settings'),
			contentTypes: Restangular.all('content-types').getList(),
			userAuth: Restangular.all('auth').customGET()
		}).then(function(responses) {
			originalResponses = _.mapObject(responses, function(response, key) {
				return [key, (response || { }).plain ? response.plain() : response];
			});

            var contentObj = _.mapObject(originalResponses.content, function(content) {
                return [content.id, content];
            });
            var campaignObj = _.mapObject(originalResponses.campaigns, function(campaign) {
                return [campaign.id, campaign];
            });

            originalResponses.tasks = _.map(originalResponses.tasks, function(task) {
                task.content = contentObj[task.contentId];
                return task;
            });
            originalResponses.campaign_tasks = _.map(originalResponses.campaign_tasks, function(task) {
                task.campaign = campaignObj[task.campaignId];
                return task;
            });
			originalResponses.campaigns = _.map(originalResponses.campaigns, function(campaign) {
				campaign.campaignId = campaign.id;
				return campaign;
			});

			angular.extend($scope, angular.copy(originalResponses));
			$scope.buyingStages = _.map(originalResponses.contentSettings.personaColumns, function(col, i) {
				return {
					// content.buyingStage is a string, so we need this 
					// to be a string also for this filter to work
					id: i + '',
					name: launch.utils.titleCase(col)
				};
			});
			$scope.steps = _.map(contentStatuses, function(status, i) {
				return {
					id: i,
					name: launch.utils.titleCase(status)
				};
			});

			// using campaignList so that we always have them all in the dropdown
			$scope.campaignList = angular.copy($scope.campaigns);
			$scope.campaignTableList = null;

			$scope.isLoaded = true;

			$scope.filters = ((originalResponses.userAuth || { }).preferences || { }).calendar || { onlyMine: false, conceptsOn: false };

			$timeout(function() {
				calendar.init(calendarConfig);
//				eventize = calendar.eventize(responses.content, responses.campaignObj);
//				eventize(responses.campaigns, responses.tasks, responses.campaign_tasks, responses.brainstorms);
			});
		});

		// Actions
		// -------------------------
		$scope.newTask = function() {
			// so... we don't actually even show campaign tasks on this page... 
			// so no need to do any more than post, which is handled in the service
			campaignTasks.openModal([], { }, true);
		};

// Helpers
		// -------------------------
		$scope.pagination = {
			pageSize: 10,
			currentPage: 1,
		};

		$scope.clearFilters = function() {
			$scope.filters = { onlyMine: false, conceptsOn: false };
		};

		$scope.saveFilters = function(filters) {
			UserService.savePreferences(user.id, 'calendar', filters, {
				success: function() {
					notify.success('Success', 'Calendar default filters saved.');
				}
			});
		};

		var filterDebouncer = _.throttle(function(filters) {
			$scope.campaigns = filterItems(originalResponses.campaigns);
			var tasks = originalResponses.tasks;

			// first filter for "my" tasks if needed
			if ($scope.filters.onlyMine) {
				tasks = _.filter(tasks, function(task) {
					return task.userId == user.id;
				});
			}

			if (!!$scope.campaigns) {
				$scope.campaignTableList = $.grep($scope.campaigns, function(c) {
					return (($scope.filters.conceptsOn) ? parseInt(c.status) === 0 : parseInt(c.status) !== 0);
				});
			}

			$scope.tasks = filterItems(tasks);

			$scope.brainstorms = filterItems(originalResponses.brainstorms);

			if (calendar)
				calendar.refresh();
		}, 300);
		$scope.$watch('filters', filterDebouncer, true);

		// :searchTerm

		$scope.filters = { onlyMine: true, conceptsOn: false };
		var searches = {
			contentTypes: 'contentTypeId',
			steps: 'stepId',
			buyingStages: 'buyingStage',
			campaigns: 'campaignId',
			users: 'userId'
		};

		function filterItems(items) {
            // first filter for "my" tasks if needed
            if ($scope.filters.onlyMine) {
                items = _.filter(items, function(task) {
                    return (task.type != 'content_task' && task.type != 'campaign_task') || task.userId == user.id;
                });
            }

			items = _.reduce(searches, function(filtered, modelKey, filterKey) {
				var array = $scope.filters[filterKey];
				if (_.isEmpty(array)) return filtered;
				return _.filter(filtered, containFilter(modelKey, array));
			}, items);

			var searchTerm = $.trim(($scope.filters.searchTerm || '').toLowerCase());
			if (!searchTerm) return items;

			return $filter('filter')(items, function(value) {
				var inResult = false;
				if (value.name) inResult = inResult || _.contains(value.name.toLowerCase(), searchTerm);
				if (value.title) inResult = inResult || _.contains(value.title.toLowerCase(), searchTerm);
				if (value.tags) inResult = inResult || _.any(value.tags, function(tag) { return _.contains((tag.tag || tag || '').toLowerCase(), searchTerm); });
				if ((value.content || { }).tags) inResult = inResult || _.any(value.content.tags, function(tag) { return _.contains((tag.tag || tag || '').toLowerCase(), searchTerm); });

				return inResult;
			});
		}

		function containFilter(prop, array) {
			return function(item) {
				// if we don't have that property for some reason, skip testing it
				if (!item.hasOwnProperty(prop) && !(item.content || { }).hasOwnProperty(prop)) return true;

				// return if an item (or item's content) has that property in the selected stuff
				return (item.content && _.contains(array, ''+item.content[prop])) || _.contains(array, ''+item[prop]);
			};
		}

		$scope.formatContentTypeItem = function(item, element, context) {
			return '<span class="' + launch.utils.getContentTypeIconClass(item.text) + '"></span> <span>' + item.text + '</span>';
		};
		$scope.formatCampaignItem = launch.utils.formatCampaignItem;
		$scope.formatBuyingStageItem = launch.utils.formatBuyingStageItem;
		$scope.formatStepItem = function(item, element, context) {
			return '<span class="' + launch.utils.getWorkflowIconCssClass(item.text) + '"></span> <span>' + item.text + '</span>';
		};
		$scope.formatUserItem = function(item, element, context) {
			if (!item.text) return element.attr('placeholder');
			var user = (_.findById($scope.users, item.id) || [])[0] || { };
			var style = ' style="background-image: url(\'' + $filter('imagePathFromObject')(user.image) + '\')"';

			return '<span class="user-image user-image-small"' + style + '></span> <span>' + item.text + '</span>';
		};
	}
]);