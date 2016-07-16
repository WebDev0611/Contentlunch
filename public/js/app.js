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


/*
    Scores a password
 */
function scorePassword(pass) {
    var score = 0;
    if (!pass)
        return score;

    // award every unique letter until 5 repetitions
    var letters = new Object();
    for (var i=0; i<pass.length; i++) {
        letters[pass[i]] = (letters[pass[i]] || 0) + 1;
        score += 5.0 / letters[pass[i]];
    }

    // bonus points for mixing it up
    var variations = {
        digits: /\d/.test(pass),
        lower: /[a-z]/.test(pass),
        upper: /[A-Z]/.test(pass),
        nonWords: /\W/.test(pass),
    };

    variationCount = 0;
    for (var check in variations) {
        variationCount += (variations[check] == true) ? 1 : 0;
    }
    score += (variationCount - 1) * 15;

    return parseInt(score);
}

/*
    Returns a color based on password strength.
 */
function checkPassStrength(pass) {
    var score = scorePassword(pass);
    if (score > 80)
        return "#77FF77";
    if (score >= 40)
        return "#FFBB77";
    if (score < 40)
        return "#FF7777";
    return "";
}

/*
    Update the Password strength indicator
 */
function updatePasswordStrengthIndicator(pass){
    $(".input-strength-indicator span").css({
        'background-color': checkPassStrength(pass),
        'width': scorePassword(pass)+"%"
    });
}

$(document).ready(function() {
    $("#password").on("keypress keyup keydown", function() {
        updatePasswordStrengthIndicator($(this).val());
    });
    updatePasswordStrengthIndicator($("#password").val());
});
//# sourceMappingURL=app.js.map
