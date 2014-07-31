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
        $scope.contentTrends_companyContentScoreTime = [];
        $scope.contentTrends_companyContentScoreTime_series = ['Count1', 'Count2'];
        var numDays = $scope.pageSettings.contentTrends.companyContentScoreTime;
        for(var i = numDays; i > 0; i--) {
            $scope.contentTrends_companyContentScoreTime.push({
                DaysAgo: i,
                Count1: parseFloat(MeasureService.getOverview().companyScore),
                Count2: parseFloat(MeasureService.getOverview().companyScore)
            })
        }
    }])
    .directive('wijmoLineChart', [function() {



        return {
            restrict: 'E',
            compile: function(tElement, tAttrs) {
                return {
                    pre: function(scope, element, attrs) {
                        var series = element.find('series');
                        var series_list = series.parent();
                        series = series.remove();

                        $.each(scope.series, function() {
                            var temp = series.clone();
                            temp.attr('label', this);
                            temp.find('y').attr('bind', this);
                            series_list.append(temp);
                        });
                    },
                    post: function(scope, element, attrs) {
                        scope.$watch('isLoaded', function(newVal, oldVal) {
                            if(newVal == true) {
                                $jquery1_11_1(element[0]).find('.wijmo-wijlinechart').wijlinechart('redraw')
                            }
                        })
                    }
                }

            },
            scope: {
                info: '=',
                series: '='
            },
            templateUrl: '/assets/views/directives/wijmo-line-chart.html'
        }
    }])