launch.module.controller('MeasureContentTrendsController', [
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

			$scope.companyContentScoreTime = 7;
			$scope.companyContentScoreGroupBy = 'author';
			$scope.individualContentScoreTrendTime = 7;
			$scope.individualContentScoreTrendGroupBy = 'author';
			$scope.individualContentScoreAverageGroupBy = 'author';

			$scope.selectedTab = 'content-trends';
		};

        self.initChartData = function () {
            $scope.companyContentScoreLine = {
                title: 'Company Content Score',
                measureFunction: measureService.getOverview,
                dateParseFunction: function(date) {
                    return {label: date.date, data: parseFloat(date.stats.companyScore)};
                }
            };

            $scope.individualContentScoreLine = {
                title: 'Individual Content Score',
                measureFunction: measureService.getOverview,
                dateParseFunction: function(date) {
                    return {label: date.date, data: parseFloat(date.stats.totalContentScore)};
                }
            };

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

		$scope.companyContentScoreTime = null;
		$scope.companyContentScoreGroupBy = null;
		$scope.individualContentScoreTrendTime = null;
		$scope.individualContentScoreTrendGroupBy = null;
		$scope.individualContentScoreAverageGroupBy = null;

		$scope.isMeasure = true;
		$scope.isLoading = false;
		$scope.isOverview = false;

		self.init();
        self.initChartData();
	}
]);