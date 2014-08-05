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
                series: ['All'],
                title: 'Total Content'
            };
        };

        $scope.getChartData = function() {

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

            return measureService.getCreated(self.loggedInUser.account.id, startDate);
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
<<<<<<< HEAD
        self.initChartData();
=======
>>>>>>> origin/master
	}
]);