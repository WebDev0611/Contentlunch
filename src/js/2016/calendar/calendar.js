/* 
------ // Calendar JS // ----
*/

(function(window,document,$){

	var activeCell = null;
	var appendTaskButton = function(cell){
		var taskButton = $('#calendar-dropdown-template').html();
		$(cell).append(taskButton);
	};
	var removeTaskButton = function(cell){
		$(cell).find('.calendar-schedule-dropdown-wrapper').fadeOut(200,function(){
			$(this).remove();
		});
	};
    
    var activateMonthly = function(){
    	var selCellVal = $(this).data('cell-date');
    	var selCell = $(this);
    	if( $(activeCell).data('cell-date') !== selCellVal ){
    		appendTaskButton(selCell);
    		removeTaskButton(activeCell);
    		activeCell = selCell;
    	}
   	};
    var activateWeekly = function(){
        var selCellVal = $(this).data('cell-date-time');
        var selCell = $(this);
        if( $(activeCell).data('cell-date-time') !== selCellVal ){
            appendTaskButton(selCell);
            removeTaskButton(activeCell);
            activeCell = selCell;
        }
    };  
   	
    $('.calendar-month-days td').mouseover(activateMontly);
    $('.calendar-week-hours td').mouseover(activateWeekly);

})(window,document,jQuery); 