launch.module
//    .controller('WijmoController', ['$scope', 'MeasureService', function($scope, MeasureService) {
//        var i, numDays;
//
//        //start of line charts
//        $scope.companyContentScoreLine = {
//            data: [],
//            series: ['Company']
//        };
//        numDays = $scope.companyContentScoreTime;
//        for(i = numDays; i > 0; i--) {
//            $scope.companyContentScoreLine.data.push({
//                DaysAgo: i,
//                Company: parseFloat(MeasureService.getOverview().companyScore)
//            })
//        }
//
//
//        $scope.individualContentScoreLine = {
//            data: [],
//            series: ['James', 'Arthur', 'Gwen']
//        };
//        numDays = $scope.individualContentScoreTrendTime;
//        for(i = numDays; i > 0; i--) {
//            $scope.individualContentScoreLine.data.push({
//                DaysAgo: i,
//                James: parseFloat(MeasureService.getOverview().companyScore),
//                Arthur: parseFloat(MeasureService.getOverview().companyScore),
//                Gwen: parseFloat(MeasureService.getOverview().companyScore)
//            })
//        }
//
//
//        $scope.contentCreatedLine = {
//            data: [],
//            series: ['James', 'Arthur', 'Gwen']
//        };
//        numDays = $scope.contentCreatedLineChartTime;
//        for(i = numDays; i > 0; i--) {
//            $scope.contentCreatedLine.data.push({
//                DaysAgo: i,
//                James: parseFloat(MeasureService.getOverview().totalContent),
//                Arthur: parseFloat(MeasureService.getOverview().totalContent),
//                Gwen: parseFloat(MeasureService.getOverview().totalContent)
//            })
//        }
//
//
//        $scope.contentLaunched = {
//            data: [],
//            series: ['James', 'Arthur', 'Gwen', 'Leslie']
//        };
//        numDays = $scope.contentLaunchedLineChartTime;
//        for(i = numDays; i > 0; i--) {
//            $scope.contentLaunched.data.push({
//                DaysAgo: i,
//                James: parseFloat(MeasureService.getOverview().totalContent) / 100,
//                Arthur: parseFloat(MeasureService.getOverview().totalContent) / 100,
//                Gwen: parseFloat(MeasureService.getOverview().totalContent) / 100,
//                Leslie: parseFloat(MeasureService.getOverview().totalContent) / 100
//            })
//        }
//
//
//        $scope.productionDays = {
//            data: [],
//            series: ['James', 'Arthur', 'Gwen']
//        };
//        numDays = $scope.productionDaysLineChartTime;
//        for(i = numDays; i > 0; i--) {
//            $scope.productionDays.data.push({
//                DaysAgo: i,
//                James: parseFloat(MeasureService.getOverview().productionDays),
//                Arthur: parseFloat(MeasureService.getOverview().productionDays),
//                Gwen: parseFloat(MeasureService.getOverview().productionDays)
//            })
//        }
//        //end of line charts
//
//        //start of pie charts
//        $scope.contentCreatedPie = {
//            data: {
//                James: parseFloat(MeasureService.getOverview().totalContent),
//                Arthur: parseFloat(MeasureService.getOverview().totalContent),
//                Gwen: parseFloat(MeasureService.getOverview().totalContent),
//                Leslie: parseFloat(MeasureService.getOverview().totalContent)
//            },
//            series: ['James', 'Arthur', 'Gwen', 'Leslie']
//        }
//
//
//        $scope.companyContentScorePie = {
//            data: {
//                James: parseFloat(MeasureService.getOverview().companyScore),
//                Arthur: parseFloat(MeasureService.getOverview().companyScore),
//                Gwen: parseFloat(MeasureService.getOverview().companyScore),
//                Leslie: parseFloat(MeasureService.getOverview().companyScore)
//            },
//            series: ['James', 'Arthur', 'Gwen', 'Leslie']
//        }
//        //end of pie charts
//
//        //start of bar charts
//        $scope.individualContentScoreBar = {
//            data: {
//                James: parseFloat(MeasureService.getOverview().companyScore),
//                Arthur: parseFloat(MeasureService.getOverview().companyScore),
//                Gwen: parseFloat(MeasureService.getOverview().companyScore),
//                Leslie: parseFloat(MeasureService.getOverview().companyScore)
//            },
//            series: ['James', 'Arthur', 'Gwen', 'Leslie']
//        }
//        //end of bar charts
//    }])
    .directive('wijmoLineChart', [function() {
        return {
            restrict: 'E',
            link: function(scope, element, attrs) {
                scope.$watch('isLoaded', function(newVal, oldVal) {
                    if(newVal == true) {
                        $jquery1_11_1(element[0]).find('.wijmo-wijlinechart').wijlinechart('redraw')
                    }
                });

                scope.getSeriesList = function() {
                    //debugger;
                    var scope = this;

                    if(this.data_days != this.days) {
                        this.data = this.$parent.getLineChartData.call(this);
                        this.data_days = this.days;
                    }

                    this.series = ['All'];
//                    if(this.group == 'all') {
//                        this.series = ['All'];
//                    }
//                    else if (this.group == 'author') {
//                        this.series = [];
//                        $.each(this.data, function(i, date){
//                            debugger;
//                            $.each(date.stats.by_user, function(i, user) {
//                                if($.inArray(user.user_id, scope.series) == -1) {
//                                    scope.series.push(user.user_id);
//                                }
//                            });
//                        });
//                        this.series = series;
//                    }

                    var series = $.map(this.series, function(value) {
                        var data = [];
                        var labels = [];

                        $.each(scope.data, function(i, date) {
                            var parsed = scope.info.dateParseFunction(date);

                            labels.push(parsed.label);
                            data.push(parsed.data);
                        });

                        return {
                            label: value,
                            data: {x: labels, y: data}
                        };
                    });

                    console.log(series);
                    return series;
                }
            },
            scope: {
                info: '=',
                days: '=',
                group: '='
            },
            templateUrl: '/assets/views/directives/wijmo-line-chart.html'
        }
    }])
    .directive('wijmoPieChart', [function() {
        return {
            restrict: 'E',
            link: function(scope, element, attrs) {
                scope.$watch('isLoaded', function(newVal, oldVal) {
                    if(newVal == true) {
                        $jquery1_11_1(element[0]).find('.wijmo-wijpiechart').wijlinechart('redraw')
                    }
                });

                scope.getSeriesList = function() {

                    var scope = this;
                    var series = $.map(this.info.series, function(value) {
                        return {
                            label: value,
                            data: scope.info.data[value]
                        };
                    });

                    console.log(series);
                    return series;
                }
            },
            scope: {
                info: '='
            },
            templateUrl: '/assets/views/directives/wijmo-pie-chart.html'
        }
    }])
    .directive('wijmoBarChart', [function() {
        return {
            restrict: 'AE',
            link: function(scope, element, attrs) {
                scope.$watch('isLoaded', function(newVal, oldVal) {
                    if(newVal == true) {
                        $jquery1_11_1(element[0]).find('.wijmo-wijbarchart').wijbarchart('redraw')
                    }

                    scope.getSeriesList = function() {

                        var scope = this;
                        var x = this.info.series;
                        var y = $.map(this.info.series, function(value) {
                            return scope.info.data[value];
                        });

                        var series = [{
                            data: {
                                x: x,
                                y: y
                            }
                        }];

                        console.log(series);
                        return series;
                    }
                })
            },
            scope: {
                info: '='
            },
            templateUrl: '/assets/views/directives/wijmo-bar-chart.html'
        };
    }])