/* 
------ // Calendar JS // ----
*/

(function(window,document,$){
    'use strict';

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

    var showCalActionOverlay = function(identifier){
        var selCellVal = $(this).data(identifier);
        var selCell = $(this);
        if( $(activeCell).data(identifier) !== selCellVal && !$(this).attr('disabled') ){
            appendTaskButton(selCell);
            removeTaskButton(activeCell);
            activeCell = selCell;
        }
    };
    var activateMonthly = function(){
        showCalActionOverlay.call(this,'cell-date');
    };
    var activateWeekly = function(){
        showCalActionOverlay.call(this,'cell-date-time');
    };      
    var activateDaily = function(){
        showCalActionOverlay.call(this,'cell-time');
    };

    $('.calendar-month-days td').mouseover(activateMonthly);
    $('.calendar-week-hours td').mouseover(activateWeekly);
    $('.calendar-day td').mouseover(activateDaily);

})(window,document,jQuery); 

