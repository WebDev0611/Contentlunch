﻿launch.module.controller('MeasureContentTrendsController', [
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

		$scope.companyContentScoreTime = null;
		$scope.companyContentScoreGroupBy = null;
		$scope.individualContentScoreTrendTime = null;
		$scope.individualContentScoreTrendGroupBy = null;
		$scope.individualContentScoreAverageGroupBy = null;

		$scope.isMeasure = true;
		$scope.isLoading = false;
		$scope.isOverview = false;

		self.init();
	}
]);