angular.module('launch')
    .controller('WijmoController', ['$scope', 'MeasureService', function($scope, MeasureService) {
        var i, numDays;

        //start of line charts
        $scope.companyContentScoreLine = {
            data: [],
            series: ['Company']
        };
        numDays = $scope.companyContentScoreTime;
        for(i = numDays; i > 0; i--) {
            $scope.companyContentScoreLine.data.push({
                DaysAgo: i,
                Company: parseFloat(MeasureService.getOverview().companyScore)
            })
        }


        $scope.individualContentScoreLine = {
            data: [],
            series: ['James', 'Arthur', 'Gwen']
        };
        numDays = $scope.individualContentScoreTrendTime;
        for(i = numDays; i > 0; i--) {
            $scope.individualContentScoreLine.data.push({
                DaysAgo: i,
                James: parseFloat(MeasureService.getOverview().companyScore),
                Arthur: parseFloat(MeasureService.getOverview().companyScore),
                Gwen: parseFloat(MeasureService.getOverview().companyScore)
            })
        }


        $scope.contentCreatedLine = {
            data: [],
            series: ['James', 'Arthur', 'Gwen']
        };
        numDays = $scope.contentCreatedLineChartTime;
        for(i = numDays; i > 0; i--) {
            $scope.contentCreatedLine.data.push({
                DaysAgo: i,
                James: parseFloat(MeasureService.getOverview().totalContent),
                Arthur: parseFloat(MeasureService.getOverview().totalContent),
                Gwen: parseFloat(MeasureService.getOverview().totalContent)
            })
        }


        $scope.contentLaunched = {
            data: [],
            series: ['James', 'Arthur', 'Gwen', 'Leslie']
        };
        numDays = $scope.contentLaunchedLineChartTime;
        for(i = numDays; i > 0; i--) {
            $scope.contentLaunched.data.push({
                DaysAgo: i,
                James: parseFloat(MeasureService.getOverview().totalContent) / 100,
                Arthur: parseFloat(MeasureService.getOverview().totalContent) / 100,
                Gwen: parseFloat(MeasureService.getOverview().totalContent) / 100,
                Leslie: parseFloat(MeasureService.getOverview().totalContent) / 100
            })
        }


        $scope.productionDays = {
            data: [],
            series: ['James', 'Arthur', 'Gwen']
        };
        numDays = $scope.productionDaysLineChartTime;
        for(i = numDays; i > 0; i--) {
            $scope.productionDays.data.push({
                DaysAgo: i,
                James: parseFloat(MeasureService.getOverview().productionDays),
                Arthur: parseFloat(MeasureService.getOverview().productionDays),
                Gwen: parseFloat(MeasureService.getOverview().productionDays)
            })
        }
        //end of line charts

        //start of pie charts
        $scope.contentCreatedPie = {
            data: {
                James: parseFloat(MeasureService.getOverview().totalContent),
                Arthur: parseFloat(MeasureService.getOverview().totalContent),
                Gwen: parseFloat(MeasureService.getOverview().totalContent),
                Leslie: parseFloat(MeasureService.getOverview().totalContent)
            },
            series: ['James', 'Arthur', 'Gwen', 'Leslie']
        }


        $scope.companyContentScorePie = {
            data: {
                James: parseFloat(MeasureService.getOverview().companyScore),
                Arthur: parseFloat(MeasureService.getOverview().companyScore),
                Gwen: parseFloat(MeasureService.getOverview().companyScore),
                Leslie: parseFloat(MeasureService.getOverview().companyScore)
            },
            series: ['James', 'Arthur', 'Gwen', 'Leslie']
        }
        //end of pie charts

        //start of bar charts
        $scope.individualContentScoreBar = {
            data: {
                James: parseFloat(MeasureService.getOverview().companyScore),
                Arthur: parseFloat(MeasureService.getOverview().companyScore),
                Gwen: parseFloat(MeasureService.getOverview().companyScore),
                Leslie: parseFloat(MeasureService.getOverview().companyScore)
            },
            series: ['James', 'Arthur', 'Gwen', 'Leslie']
        }
        //end of bar charts
    }])
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
                    function formatSeries(seriesList, x_prop, y_prop) {
                        var data = [];
                        var labels = [];

                        $.each(seriesList, function() {
                            labels.push(this[x_prop]);
                            data.push(this[y_prop]);
                        });

                        return {
                            label: y_prop,
                            data: {x: labels, y: data}
                        };
                    }

                    var scope = this;
                    var series = $.map(this.info.series, function(value) {
                        return formatSeries(scope.info.data, 'DaysAgo', value)
                    });

                    console.log(series);
                    return series;
                }
            },
            scope: {
                info: '='
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