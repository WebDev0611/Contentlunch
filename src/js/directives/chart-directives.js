angular.module('launch')
    .controller('WijmoBarController', ['$scope', function($scope) {
        $scope.list = [
            { Maker: "Ford", Sales: .05 },
            { Maker: "GM", Sales: .04 },
            { Maker: "Chrysler", Sales: .21 },
            { Maker: "Toyota", Sales: .27 },
            { Maker: "Nissan", Sales: .1 },
            { Maker: "Honda", Sales: .24 }
        ];
    }])
    .directive('wijmoBarChart', [function() {
        return {
            restrict: 'AE',
            link: function(scope, element, attrs) {
                scope.$watch('isLoaded', function(newVal, oldVal) {
                    if(newVal == true) {
                        $jquery1_11_1(element[0]).find('.wijmo-wijbarchart').wijbarchart('redraw')
                    }
                })
            },
            templateUrl: '/assets/views/directives/wijmo-bar-chart.html'
        };
    }])



    .controller('WijmoLineController', ['$scope', 'MeasureService', function($scope, MeasureService) {
        var i, numDays;

        $scope.contentTrends_companyContentScore = {
            data: [],
            series: ['Company']
        };
        numDays = $scope.pageSettings.contentTrends.companyContentScoreTime;
        for(i = numDays; i > 0; i--) {
            $scope.contentTrends_companyContentScore.data.push({
                DaysAgo: i,
                Company: parseFloat(MeasureService.getOverview().companyScore)
            })
        }


        $scope.contentTrends_individualContentScore = {
            data: [],
            series: ['James', 'Arthur', 'Gwen']
        };
        numDays = $scope.pageSettings.contentTrends.individualContentScoreTrendTime;
        for(i = numDays; i > 0; i--) {
            $scope.contentTrends_individualContentScore.data.push({
                DaysAgo: i,
                James: parseFloat(MeasureService.getOverview().companyScore),
                Arthur: parseFloat(MeasureService.getOverview().companyScore),
                Gwen: parseFloat(MeasureService.getOverview().companyScore)
            })
        }


        $scope.creationStats_contentCreatedLineChart = {
            data: [],
            series: ['James', 'Arthur', 'Gwen']
        };
        numDays = $scope.pageSettings.creationStats.contentCreatedLineChartTime;
        for(i = numDays; i > 0; i--) {
            $scope.creationStats_contentCreatedLineChart.data.push({
                DaysAgo: i,
                James: parseFloat(MeasureService.getOverview().totalContent),
                Arthur: parseFloat(MeasureService.getOverview().totalContent),
                Gwen: parseFloat(MeasureService.getOverview().totalContent)
            })
        }


        $scope.creationStats_contentLaunchedLineChart = {
            data: [],
            series: ['James', 'Arthur', 'Gwen', 'Leslie']
        };
        numDays = $scope.pageSettings.creationStats.contentLaunchedLineChartTime;
        for(i = numDays; i > 0; i--) {
            $scope.creationStats_contentLaunchedLineChart.data.push({
                DaysAgo: i,
                James: parseFloat(MeasureService.getOverview().totalContent) / 100,
                Arthur: parseFloat(MeasureService.getOverview().totalContent) / 100,
                Gwen: parseFloat(MeasureService.getOverview().totalContent) / 100,
                Leslie: parseFloat(MeasureService.getOverview().totalContent) / 100
            })
        }


        $scope.creationStats_productionDaysLineChart = {
            data: [],
            series: ['James', 'Arthur', 'Gwen']
        };
        numDays = $scope.pageSettings.creationStats.productionDaysLineChartTime;
        for(i = numDays; i > 0; i--) {
            $scope.creationStats_productionDaysLineChart.data.push({
                DaysAgo: i,
                James: parseFloat(MeasureService.getOverview().productionDays),
                Arthur: parseFloat(MeasureService.getOverview().productionDays),
                Gwen: parseFloat(MeasureService.getOverview().productionDays)
            })
        }
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