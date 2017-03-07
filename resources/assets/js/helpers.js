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
        format: 'YYYY-MM-DD HH:mm',
        sideBySide: true,
    });

    $('#content-due-date').datetimepicker({
        format: 'YYYY-MM-DD'
    });
});

function openTaskModal() {
    $("#addTaskModal").modal('show');
}

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
        campaign_id: getCampaignId(),
        calendar_id: getCalendarId()
    };
}

function getCampaignId() {
    let element = $('#is_campaign_task');
    let campaignId = null;

    if (element.length && element.val() === 'on') {
        campaignId = element.data('id');
    }

    return campaignId;
}

function getContentId() {
    var element = $('#is_content_task');
    var contentId = null;

    if (element.length && element.val() === 'on') {
        contentId = element.data('id');
    }

    return contentId;
}

function getCalendarId() {
    let element = $('#is_calendar_task');
    let calendarId = null;

    if (element.length && element.val() === 'on') {
        calendarId = element.data('id');
    }

    return calendarId;
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

function showUpgradeAlert(message) {
    return swal({
        title: 'Error',
        type: 'info',
        text: message,
        showCloseButton: true,
        showCancelButton: true,
        confirmButtonColor: "#6944B6",
        confirmButtonText: `<a style="color:#fff" href="/subscription">Upgrade now</a>`,
    });
}