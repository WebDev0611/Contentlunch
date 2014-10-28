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
            function fieldDateParse(field) {
                return function(date, groupBy, group) {
                    var sum = 0;
                    if(groupBy == 'all' || !groupBy) {
                        $.each(date.stats.by_user, function(i, user) {
                            sum += parseFloat(user[field]);
                        });
                    }
                    else if(groupBy == 'author') {
                        $.each(date.stats.by_user, function(i, user) {
                            if(user.user_id == group) {
                                sum += parseFloat(user[field]);
                            }
                        })
                    }
                    else if(groupBy == 'buying-stage') {
                        $.each(date.stats.by_buying_stage, function(i, stage) {
                            if(stage.buying_stage == group) {
                                sum += parseFloat(stage[field]);
                            }
                        })
                    }
                    else if(groupBy == 'content-type') {
                        $.each(date.stats.by_content_type, function(i, type) {
                            if(type.content_type_id == group) {
                                sum += parseFloat(type[field]);
                            }
                        })
                    }
                    return {label: date.date, data: sum};
                }
            }

            $scope.companyContentScoreLine = {
                title: 'Company Content Score',
                measureFunction: measureService.getScore,
                dateParseFunction: fieldDateParse('score')
            };

            $scope.companyContentScorePie = {
                title: 'Content Score Breakdown',
                measureFunction: measureService.getScore,
                dateParseFunction: fieldDateParse('score')
            };

            $scope.individualContentScoreLine = {
                title: 'Individual Content Score',
                measureFunction: measureService.getScore,
                dateParseFunction: fieldDateParse('score')
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