function showTooltip(x, y, contents) {
     $('<div id="tooltip">' + contents + '</div>').css({
         position: 'absolute',
         display: 'none',
         top: y + 5,
         left: x + 15,
         border: '1px solid #333',
         padding: '4px',
         color: '#fff',
         'border-radius': '3px',
         'background-color': '#333',
         opacity: 0.80
     }).appendTo("body").fadeIn(200);
}

var Dashboard = function () { 
	return {
	siteSelect : function() {
		$('#dashboard-sitename').change(function() {
			$('#dashboard-site-form').submit();
		});
	},
	
	loginDifferentBusiness : function() {
		if($('#business-login').length) {
			var $business_login = $('#business-login');
			$business_login.select2();
			
			$business_login.on('change', function (e) {
				var business_id = $(e.target).val();
				
				$.ajax({
					type:'get',
					url: '/admin/login-diff-business/'+business_id,
					success: function() {
						location.reload();
					}
				});
			});
		}
	},
	
	loginDifferentUser : function() {
		if($('#dashboard-login').length) {
			var $dashboard_login = $('#dashboard-login');
			$dashboard_login.select2();
			
			$dashboard_login.on('change', function (e) { 
				var user_id = $(e.target).val();
				
				$.ajax({
					type:'get',
					url: '/admin/login-diff-user/'+user_id,
					success: function() {
						location.reload();
					}
				});
			});
		}
		
		if($('#loginuserback').length) {
			$('#loginuserback')[0].onclick = function() { return false; };
			$('#loginuserback').click(function() {
				$.ajax({
					type:'get',
					url: '/admin/login-diff-user/real_id',
					success: function() {
						window.location="/admin/dashboard";
					}
				});
				
			});
		}
	},
    
    dashboardDaterange: function () {
    	var date_holder = JSON.parse($('#date_holder').html());
        var start_date = date_holder[ Object.keys(date_holder).sort().shift() ];
        var end_date = date_holder[ Object.keys(date_holder).sort().pop() ];
        var object = this;
        $('#dashboard-report-range').daterangepicker({
                opens: 'left',
                startDate: moment(start_date),
                endDate: moment(end_date),
                //minDate: '01/01/2012',
                //maxDate: '12/31/2014',
                dateLimit: {
                    days: 60
                },
                showDropdowns: false,
                showWeekNumbers: true,
                timePicker: false,
                timePickerIncrement: 1,
                timePicker12Hour: true,
                ranges: {
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
                    'Last 7 Days': [moment().subtract('days', 6), moment()],
                    'Last 30 Days': [moment().subtract('days', 29), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
                },
                buttonClasses: ['btn btn-sm'],
                applyClass: ' blue',
                cancelClass: 'default',
                format: 'MM/DD/YYYY',
                separator: ' to ',
                locale: {
                    applyLabel: 'Apply',
                    fromLabel: 'From',
                    toLabel: 'To',
                    customRangeLabel: 'Custom Range',
                    daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
                    monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                    firstDay: 1
                }
            },
            function (start, end) {
                $('#dashboard-report-range span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
            }
        );

        $('#dashboard-report-range span').html(moment(start_date).format('MMMM D, YYYY') + ' - ' + moment(end_date).format('MMMM D, YYYY'));
        $('#dashboard-report-range').show();
        
        $('.daterangepicker .applyBtn, .ranges li:not(:last-child)').click(function() {
        	$.blockUI({
        		message: $('<img />').attr('src', '/working/metronic_v3.1.3/metronic/assets/global/img/loading-spinner-grey.gif'), 
        		css: { padding:'2px 0 0 0', width:32, height:32, borderRadius:'50% !important', left:'50%' } 
        	});
        	var start_date = $('.daterangepicker_start_input input').val();
        	var end_date = $('.daterangepicker_end_input input').val();
        	
        	$.post('/admin/dashboard/', {start_date:start_date, end_date:end_date},
        		function(responseText, responseStatus){
        			var values = ['date_holder', 'source', 'city_views'];
        			var page_container = $(responseText).find('#general-stats').parent().parent().parent();
        			
        			//General stats
        			var stats = [
        			    {'name':'avgtimesite','value':100}, {'name':'pages-session','value':100},
        			    {'name':'totalvisits','value':100}, {'name':'newvisits'},{'name':'bounce'}
        			];
        			
        			$.each(stats, function(index, opt) {
        				var stat_text = $(responseText).find('#general-stats .number.'+opt.name+' span').html();
        				
        				var value = Number(typeof(opt.value) == 'undefined' ? $.trim(stat_text) : opt.value);
        				
            			$('#general-stats .number.'+opt.name).data('easyPieChart').update(value);
            			$('#general-stats .number.'+opt.name+' span').html(stat_text);
        			});
        			
        			//visit chart
        			//$(responseText).find('#desktop_views').html()
        			var desktop_views = eval($(responseText).find('#desktop_views').html());
        			var mobile_views = eval($(responseText).find('#mobile_views').html());
        			var tablet_views = eval($(responseText).find('#tablet_views').html());
        			
        			
        			var date_holder = JSON.parse($(responseText).find('#date_holder').html());
        			
        			object.visitChart(desktop_views, mobile_views, tablet_views, date_holder);
        			
        			//referrals
        			var source = JSON.parse($(responseText).find('#source').html());
        			object.referralChart(source);
        			//location
        			var cities = eval($(responseText).find('#city_views').html());
        			object.locationChart(cities);
        			
        			$.unblockUI();
    			 }
        	 );
        });
    },
    
	referralChart : function(results) {
		//results to percentages
		var total = 0;
		$.each(results, function(key, val) {
			total += Number(val);
		});
		
		var dataPoints = [];
		$.each(results, function(key, val) {
			var label_name = key.replace('.', '\.');
			var percent_number = (Number(val) / total) * 100;
			var percent = percent_number.toFixed(1);
			if(percent < 1) { //1% hack
				percent = 1;
			}
			var dataPoint = {label: label_name, legendText:label_name, y:percent};
			dataPoints.push(dataPoint);
		});
		
		console.log('dataPoints', dataPoints);
        $("#chartContainer").CanvasJSChart({ 
    		legend :{ 
    			verticalAlign: "center", 
    			horizontalAlign: "right" 
    		}, 
    		data: [{ 
    			type: "pie", 
    			showInLegend: true, 
    			toolTipContent: "{label} <br/> {y} %", 
    			indexLabel: "{y} %", 
    			dataPoints: dataPoints 
    		}] 
    	}); 
	},
	locationChart : function(location) {
		// horizontal bar chart:
        var data1 = [
            [10, 10], [20, 20], [30, 30], [40, 40], [50, 50]
        ];
     
        var options = {
                series:{
                    bars:{show: true}
                },
                bars:{
                	horizontal:true,
                    barWidth:0.5,
                    lineWidth: 0, // in pixels
                    shadowSize: 0,
                    align: 'center'
                },
                yaxis: {
                    mode: "categories",
                },
                grid:{
                	tickColor: "#eee",
					borderColor: "#eee",
					borderWidth: 1
                }
        };
     
        $.plot($("#location"), [location], options);
	},
	visitChart : function(desktop, tablet, mobile, dates) {
         var plot = $.plot($("#dvtvm"), [{
                 data: desktop,
                 label: "Desktop Visits",
                 lines: {
                     lineWidth: 1,
                 },
                 shadowSize: 0

             }, {
                 data: tablet,
                 label: "Tablet Views",
                 lines: {
                     lineWidth: 1,
                 },
                 shadowSize: 0
             }, {
                 data: mobile,
                 label: "Mobile Views",
                 lines: {
                     lineWidth: 1,
                 },
                 shadowSize: 0
             }
         ], {
             series: {
                 lines: {
                     show: true,
                     lineWidth: 2,
                     fill: true,
                     fillColor: {
                         colors: [{
                                 opacity: 0.05
                             }, {
                                 opacity: 0.01
                             }
                         ]
                     }
                 },
                 points: {
                     show: true,
                     radius: 3,
                     lineWidth: 1
                 },
                 shadowSize: 2
             },
             grid: {
                 hoverable: true,
                 clickable: true,
                 tickColor: "#eee",
                 borderColor: "#eee",
                 borderWidth: 1
             },
             colors: ["#d12610", "#37b7f3", "#52e136"],
             xaxis: {
                 tickLength: 0,
                 tickDecimals: 0,
                 mode: "categories",
                 font: {
                     lineHeight: 14,
                     style: "normal",
                     variant: "small-caps",
                     color: "#6F7B8A"
                 }
             }
             /*xaxis: {
                 ticks: 11,
                 tickDecimals: 0,
                 tickColor: "#eee",
             }*/,
             yaxis: {
                 ticks: 11,
                 tickDecimals: 0,
                 tickColor: "#eee",
             }
         });

         var previousPoint = null;
         $("#dvtvm").bind("plothover", function (event, pos, item) {
             //$("#x").text(pos.x.toFixed(2));
        	 $("#x").text(pos.x.toFixed(2));
             $("#y").text(pos.y.toFixed(2));

             if (item) {
                 if (previousPoint != item.dataIndex) {
                     previousPoint = item.dataIndex;

                     $("#tooltip").remove();
                     var visits = item.datapoint[1];
                     var date_time = dates[item.series.data[item.dataIndex][0]]; //item.series.label

                     showTooltip(item.pageX, item.pageY, visits + " visits on " + date_time);
                 }
             } else {
                 $("#tooltip").remove();
                 previousPoint = null;
             }
         });
	}
}}();