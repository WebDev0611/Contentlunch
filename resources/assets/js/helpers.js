/* misc js -- global or sitewide methods here */

//handles the create modal for the site
$(function(){
	$('.btn-create').click(function(){
		$('body').toggleClass('showcreate');
	});

	$('div.create-overlay').click(function(){
		$('body').toggleClass('showcreate');
	});

	$('div.create-overlay li').click(function(event){
 		event.stopPropagation();
	});
});

var format_time_ago = function(time){
	var cur = moment();
	var hours_ago = cur.diff( moment(time*1000) ,'hours');
	var days_ago = cur.diff( moment(time*1000) ,'days');
	var minutes_ago = cur.diff( moment(time*1000) ,'minutes');

	if(days_ago > 0){
		return days_ago + ' DAYS AGO';
	}else if(hours_ago > 0){
		return hours_ago + ' HOURS AGO';
	}else if(minutes_ago > 0){
		return minutes_ago + ' MINUTES AGO';
	}else{
		return 'JUST NOW';
	}
};


//handles the task modal for the site
$(function(){

	$('.add-task-action').click(function(){
	 $("#addTaskModal").modal('show');
	});

    $('#task-start-date').datetimepicker({
        format: 'YYYY-MM-DD',
        sideBySide: true,
    });

    $('#task-due-date').datetimepicker({
        format: 'YYYY-MM-DD',
        sideBySide: true,
    });

});

//adds the task from any page
var loadIMG = $('<img src="/images/loading.gif" style="max-height:30px;" />');
var add_task = function(callback) {
    //so hacky ;)

    //need proper validation here
    if (isTaskDataValid()) {
        $('#task-menu').prepend(loadIMG);
        $.ajax({
            url: '/task/add',
            type: 'post',
            data: getTaskData(),
            headers: getCSRFHeader(),
            dataType: 'json',
            success: addedTaskCallback(callback)
        });
    }
};

function isTaskDataValid() {
    return getTaskData().name.length > 2;
}

function addedTaskCallback(callback) {
    return function(res) {
        $(loadIMG).remove();
        clearTaskInputs();

        if ('function' === typeof callback) {
            callback(res);
        }
    }
}

function getCSRFHeader() {
    return {
        'X-CSRF-TOKEN': $('input[name=_token]').val()
    };
}

function getTaskData() {
    return {
        name: $('#task-name').val(),
        start_date: $('#task-start-date').val(),
        due_date: $('#task-due-date').val(),
        explanation: $('#task-explanation').val(),
        url: $('#task-url').val(),
        attachments: getTaskAttachments()
    }
}

function getTaskAttachments() {
    var fileInputs = $('#addTaskModal *[name=\'files[]\']');

    return fileInputs.toArray().map(function(element, index) {
        return element.value;
    });
}

function clearTaskInputs() {
    $('#task-name').val('');
    $('#task-start-date').val('');
    $('#task-due-date').val('');
    $('#task-explanation').val('');
    $('#task-url').val('');
}