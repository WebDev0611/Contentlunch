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

			$scope.contentTrends = {
				companyContentScoreTime: 7,
				companyContentScoreGroupBy: 'author',
				individualContentScoreTrendTime: 7,
				individualContentScoreTrendGroupBy: 'author',
				individualContentScoreAverageGroupBy: 'author'
			};

			$scope.selectedTab = 'content-trends';
		};

		$scope.isMeasure = true;
		$scope.isLoading = false;
		$scope.isOverview = false;

		self.init();
	}
]);