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
    }]);

//angular.module('launch').directive('wijmoBarChart', [function () {
//    function link(scope, element, attrs) {
//        element.wijbarchart({
//            textStyle: {
//                "font-size": "13px",
//                "font-family": '"Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif'
//            },
//            header: {
//                text: "Sales By State"
//            },
//            clusterWidth: 60, //size of each bar (or group of bars if multiple series are used)
//            marginTop: 5, //Lessen the top markgin
//            marginRight: 60, //Add more right margin to make sure header text aligns with axis text
//            axis: {
//                y: {
//                    text: "USD (thousands)",
//                    textStyle: {
//                        "font-weight": "normal",
//                        "margin-bottom": 5, //space the axis text away from the axis line
//                    },
//                    min: 0, //Minimum value for axis
//                    max: 8000, //Maximum value for axis
//                    autoMin: false, //Tell the chart not to automatically generate minimum value for axis
//                    autoMax: false, //Tell the chart not to automatically generate maximum value for axis
//                    gridMajor: { visible: false }, //hide gridMajor lines
//                    visible: true, //show line along axis
//                    tickMajor: {
//                        position: "outside", //position tick marks outside of axis line
//                        style: {
//                            stroke: "#999999" //Make the tick marks match axis line color
//                        }
//                    },
//                    annoFormatString: 'n0' //Format values on axis as number with 0 decimal places. For example, 4.00 would be shown as 4
//                },
//                x: {
//                    visible: false,
//                    compass: "north", //Position the x axis labels on top of the chart
//                    textStyle: {
//                        "font-weight": "normal"
//                    }
//                }
//            },
//            showChartLabels: false, //Hide labels on each bar
//            hint: {
//                content: function () {
//                    return this.x + ': ' + Globalize.format(this.y * 1000, 'c0'); //Display x value and format y value as currency after multiplying by 1000
//                },
//                contentStyle: {
//                    "font-size": "14px",
//                    "font-family": '"Segoe UI", Frutiger, "Frutiger Linotype", "Dejavu Sans", "Helvetica Neue", Arial, sans-serif'
//                },
//                style: {
//                    fill: "#444444"
//                }
//            },
//            shadow: false,
//            seriesList: [{
//                legendEntry: false, //Prevent series from being added to legend
//                data: {
//                    x: [
//                        "Ohio",
//                        "Florida",
//                        "Arizona",
//                        "Utah",
//                        "Colorado",
//                        "Hawaii",
//                        "Texas",
//                        "Maryland",
//                        "North Carolina",
//                        "Maryland",
//                        "Oregon",
//                        "Washington",
//                        "New York",
//                        "California",
//                        "Pennsylvannia"],
//                    y: [
//                        1800,
//                        2250,
//                        2860,
//                        2880,
//                        2900,
//                        2920,
//                        3070,
//                        3190,
//                        3520,
//                        4100,
//                        4280,
//                        4320,
//                        580,
//                        7040,
//                        7650]
//                }
//            }],
//            seriesStyles: [
//                {
//                    fill: "rgb(136,189,230)", //fill color of bar
//                    stroke: "none" //border color of bar
//                }
//            ]
//        });
//    }
//    return {
//        restrict: 'AE',
//        templateUrl: '/assets/views/directives/wijmo-bar-chart.html',
//        link: link
//    };
//}]);