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

			$scope.creationStats = {
				contentCreatedLineChartTime: 7,
				contentCreatedLineChartGroupBy: 'all',
				contentCreatedPieChart: 'author',
				contentLaunchedLineChartTime: 7,
				contentLaunchedLineChartGroupBy: 'all',
				productionDaysLineChartTime: 30,
				productionDaysLineChartGroupBy: 'all'
			};
		};

		$scope.isMeasure = true;
		$scope.isLoading = false;
		$scope.isOverview = false;

		self.init();
	}
]);