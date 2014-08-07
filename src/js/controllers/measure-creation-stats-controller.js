launch.module.controller('MeasureCreationStatsController', [
	'$scope', '$location', '$filter', 'AuthService', 'UserService', 'ContentService', 'CampaignService', 'MeasureService', 'NotificationService', function ($scope, $location, $filter, authService, userService, contentService, campaignService, measureService, notificationService) {
		var self = this;

		self.loggedInUser = null;

		self.ajaxHandler = {
			success: function (r) {

			},
			error: function (r) {
				launch.utils.handleAjaxErrorResponse(r, notificationService);
			}
		};

		self.init = function () {
			self.loggedInUser = authService.userInfo();

			$scope.selectedTab = 'creation-stats';

			$scope.contentCreatedLineChartTime = 7;
			$scope.contentCreatedLineChartGroupBy = 'all';
			$scope.contentCreatedPieChartGroupBy = 'author';
			$scope.contentLaunchedBarChartTime = 7;
			$scope.contentLaunchedBarChartGroupBy = 'all';
			$scope.productionDaysLineChartTime = 30;
			$scope.productionDaysLineChartGroupBy = 'all';
		};

        self.initChartData = function () {
            function countDateParse(date, groupBy, group) {
                var sum = 0;
                if(groupBy == 'all' || !groupBy) {
                    $.each(date.stats.by_user, function(i, user) {
                        sum += user.count;
                    });
                }
                else if(groupBy == 'author') {
                    $.each(date.stats.by_user, function(i, user) {
                        if(user.user_id == group) {
                            sum += user.count;
                        }
                    })
                }
                else if(groupBy == 'buying-stage') {
                    $.each(date.stats.by_buying_stage, function(i, stage) {
                        if(stage.buying_stage == group) {
                            sum += stage.count;
                        }
                    })
                }
                else if(groupBy == 'content-type') {
                    $.each(date.stats.by_content_type, function(i, type) {
                        if(type.content_type_id == group) {
                            sum += type.count;
                        }
                    })
                }
                return {label: date.date, data: sum};
            }

            $scope.contentCreatedLine = {
                title: 'Total Content',
                measureFunction: measureService.getCreated,
                dateParseFunction: countDateParse
            };

            $scope.contentLaunched = {
                title: 'Content Launched',
                measureFunction: measureService.getLaunched,
                dateParseFunction: countDateParse
            };

            $scope.productionDays = {
                title: 'Average Production Days',
                measureFunction: measureService.getTiming,
                dateParseFunction: function(date, groupBy, group) {
                    var sum = 0;
                    var average = 0;

                    if(groupBy == 'all' || !groupBy) {
                        $.each(date.stats.by_user, function(i, user) {
                            sum += parseFloat(user.average_seconds);
                        });
                        average = date.stats.by_user.length > 0 ? sum / date.stats.by_user.length : 0;
                    }
                    else if(groupBy == 'author') {
                        $.each(date.stats.by_user, function(i, user) {
                            if(user.user_id == group) {
                                sum += parseFloat(user.average_seconds);
                            }
                        });
                        average = date.stats.by_user.length > 0 ? sum / date.stats.by_user.length : 0;
                    }
                    else if(groupBy == 'buying-stage') {
                        $.each(date.stats.by_buying_stage, function(i, stage) {
                            if(stage.buying_stage == group) {
                                sum += parseFloat(stage.average_seconds);
                            }
                        });
                        average = date.stats.by_buying_stage.length > 0 ? sum / date.stats.by_buying_stage.length : 0;
                    }
                    else if(groupBy == 'content-type') {
                        $.each(date.stats.by_content_type, function(i, type) {
                            if(type.content_type_id == group) {
                                sum += parseFloat(type.average_seconds);
                            }
                        });
                        average = date.stats.by_content_type.length > 0 ? sum / date.stats.by_content_type.length : 0;
                    }

                    return {label: date.date, data: average}
                }
            };

            $scope.contentCreatedPie = {
                title: 'Total Content',
                measureFunction: measureService.getCreated,
                dateParseFunction: countDateParse
            };
        };

        $scope.getChartData = function() {

            var startDate;
            if(!this.days) {
                startDate = moment('1970-01-01')
            }
            else if(this.days == 'Q') {
                startDate = moment().startOf('quarter');
            }
            else if(this.days == 'Y') {
                startDate = moment().startOf('year');
            }
            else if(this.days == 'A'){
                startDate = moment().startOf('year');
            }
            else {
                startDate = moment().subtract(parseInt(this.days), 'days');
            }

            return this.info.measureFunction(self.loggedInUser.account.id, startDate.format('YYYY-MM-DD'));
        };

		$scope.contentCreatedLineChartTime = null;
		$scope.contentCreatedLineChartGroupBy = null;
		$scope.contentCreatedPieChartGroupBy = null;
		$scope.contentLaunchedBarChartTime = null;
		$scope.contentLaunchedBarChartGroupBy = null;
		$scope.productionDaysLineChartTime = null;
		$scope.productionDaysLineChartGroupBy = null;

		$scope.isMeasure = true;
		$scope.isLoading = false;
		$scope.isOverview = false;

		self.init();
        self.initChartData();
	}
]);