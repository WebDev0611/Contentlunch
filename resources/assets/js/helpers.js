/* misc js -- global or sitewide methods here */

//handles the create modal for the site
$(function () {
    $('.btn-create').click(function () {
        $('body').toggleClass('showcreate');
    });

    $('div.create-overlay').click(function () {
        $('body').toggleClass('showcreate');
    });

    $('div.create-overlay li').click(function (event) {
        event.stopPropagation();
    });
});

// Initialize tooltips.
$('[data-toggle="tooltip"]').tooltip();

function format_time_ago(time) {
    var cur = moment();
    var hours_ago = cur.diff(moment(time * 1000), 'hours');
    var days_ago = cur.diff(moment(time * 1000), 'days');
    var minutes_ago = cur.diff(moment(time * 1000), 'minutes');

    if (days_ago > 0) {
        return days_ago + ' DAYS AGO';
    } else if (hours_ago > 0) {
        return hours_ago + ' HOURS AGO';
    } else if (minutes_ago > 0) {
        return minutes_ago + ' MINUTES AGO';
    } else {
        return 'JUST NOW';
    }
};


//handles the task modal for the site
$(function () {

    $('.add-task-action').click(function () {
        $("#addTaskModal").modal('show');
    });

    $('#task-start-date').datetimepicker({
        format: 'YYYY-MM-DD HH:mm',
        sideBySide: true,
    });

    $('#task-due-date').datetimepicker({
        //format: 'DD/MM/YY',
        format: 'YYYY-MM-DD HH:mm',
        sideBySide: true,
    });

});

//adds the task from any page
var loadIMG = $('<img src="/images/loading.gif" style="max-height:30px;" />');

function add_task(callback) {
    if (isTaskDataValid()) {
        $('#task-menu').prepend(loadIMG);

        return $.ajax({
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
    return function (res) {
        $(loadIMG).remove();
        clearTaskInputs();

        if ('function' === typeof callback) {
            callback(res);
        }
    }
}

function getToken() {
    return $('input[name=_token]').val();
}

function getCSRFHeader() {
    return {
        'X-CSRF-TOKEN': getToken()
    };
}

function getJsonHeader() {
    return {
        'X-CSRF-TOKEN': getToken(),
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    };
}

function getTaskData() {
    return {
        name: $('#task-name').val(),
        start_date: $('#task-start-date').val(),
        due_date: $('#task-due-date').val(),
        explanation: $('#task-explanation').val(),
        url: $('#task-url').val(),
        attachments: getTaskAttachments(),
        assigned_users: getTaskAssignments(),
        content_id: getContentId(),
    };
}

function getContentId() {
    var element = $('#is_content_task');
    var contentId = null;

    if ((element.length) && (element.val() === 'on')) {
        contentId = element.data('id');
    }

    return contentId;
}

function getTaskAttachments() {
    var fileInputs = $('#addTaskModal *[name=\'files[]\']');

    return fileInputs.toArray().map(function (element, index) {
        return element.value;
    });
}

function getTaskAssignments() {
    return $('#task-assignment :checked')
        .toArray()
        .map(function (checkbox) {
            return $(checkbox).data('id');
        });
}

function clearTaskInputs() {
    $('#task-name').val('');
    $('#task-start-date').val('');
    $('#task-due-date').val('');
    $('#task-explanation').val('');
    $('#task-url').val('');

    $('.task-attached-files').remove();
    window.taskAttachmentUploader.removeAllFiles();
}

// Calendar helpers
function add_calendar(callback) {

    if (isCalendarDataValid()) {
        $('#add-calendar-button').prepend(loadIMG);

        return $.ajax({
            url: '/calendar/add',
            type: 'post',
            data: getCalendarData(),
            headers: getCSRFHeader(),
            dataType: 'json',
            success: addedCalendarCallback(callback)
        });
    }
};

function isCalendarDataValid() {
    let data = getCalendarData();

    let name_ok = data.name.length > 2;
    let content_ok = data.content_type_ids.length > 0;

    if (name_ok && (data.show_tasks != null || data.show_ideas != null || content_ok)) {
        return true;
    }

    return false;
}

function getCalendarData() {
    let data = {};
    data.name = $('#calendar_name').val();
    data.color = $('.checkbox-color input:checked').val();
    data.show_tasks = $('#show_tasks:checked').val();
    data.show_ideas = $('#show_ideas:checked').val();

    data.content_type_ids = [];
    $('.checkbox-content-types input:checked').each(function () {
        data.content_type_ids.push($(this).val());
    });

    return data;
}

function addedCalendarCallback(callback) {
    return function (res) {
        $(loadIMG).remove();
        clearCalendarInputs();

        if ('function' === typeof callback) {
            callback(res);
        }
    }
}

function clearCalendarInputs() {
    $('#calendar_name').val('');
    $('#show_tasks:checked').click();
    $('#show_ideas:checked').click();
    $('.checkbox-color input:checked').click();
    $('.checkbox-content-types input:checked').click();
}

// Allow only one color to be selected
$(".checkbox-color input").change(function () {
    let that = $(this);
    // unchecking with .attr method doesn't work here so we improvise
    $('.checkbox-color input:checked').click();
    $('#' + that.id).click();
});
