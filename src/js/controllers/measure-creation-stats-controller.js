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
			$scope.contentCreatedPieChart = 'author';
			$scope.contentLaunchedLineChartTime = 7;
			$scope.contentLaunchedLineChartGroupBy = 'all';
			$scope.productionDaysLineChartTime = 30;
			$scope.productionDaysLineChartGroupBy = 'all';
		};

        self.initChartData = function () {
            $scope.contentCreatedLine = {
                title: 'Total Content',
                measureFunction: measureService.getCreated,
                dateParseFunction: function(date) {
                    var sum = 0;
                    $.each(date.stats.by_user, function(i, user) {
                        sum += user.count;
                    });
                    return {label: date.date, data: sum};
                }
            };

            $scope.contentLaunched = {
                title: 'Content Launched',
                measureFunction: measureService.getLaunched,
                dateParseFunction: function(date) {
                    var sum = 0;
                    $.each(date.stats.by_user, function(i, user) {
                        sum += user.count;
                    });
                    return {label: date.date, data: sum};
                }
            }

            $scope.productionDays = {
                title: 'Average Production Days',
                measureFunction: measureService.getTiming,
                dateParseFunction: function(date) {
                    var sum = 0;
                    $.each(date.stats.by_user, function(i, user) {
                        sum += parseFloat(user.average_seconds);
                    });
                    var average = date.stats.by_user.length > 0 ? sum / date.stats.by_user.length : 0;

                    return {label: date.date, data: average}
                }
            }
        };

        $scope.getLineChartData = function() {

            var startDate;
            if(this.days == 'Q') {
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
		$scope.contentCreatedPieChart = null;
		$scope.contentLaunchedLineChartTime = null;
		$scope.contentLaunchedLineChartGroupBy = null;
		$scope.productionDaysLineChartTime = null;
		$scope.productionDaysLineChartGroupBy = null;

		$scope.isMeasure = true;
		$scope.isLoading = false;
		$scope.isOverview = false;

		self.init();
        self.initChartData();
	}
]);