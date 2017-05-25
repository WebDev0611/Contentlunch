'use strict';

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
        $("#addTaskModal").modal({ backdrop: 'static' });
    });

    $('#task-start-date').datetimepicker({
        format: 'MM/DD/YYYY HH:mm',
        sideBySide: true,
    });

    $('#task-due-date').datetimepicker({
        format: 'MM/DD/YYYY HH:mm',
        sideBySide: true,
    });

    $('#content-due-date').datetimepicker({
        format: 'MM/DD/YYYY'
    });
});

function openTaskModal() {
    $("#addTaskModal").modal({ backdrop: 'static' });
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
    let element = $('#is_calendar_item');
    let calendarId = null;

    if (element.length && element.val() === 'on') {
        calendarId = element.data('id');
    } else if($('#calendar-id').length) {
        calendarId = $('#calendar-id').val();
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
        confirmButtonText: `<a style="color:#fff" href="/settings/subscription">Upgrade now</a>`,
    });
}

function camelize(str) {
    return str.replace(/(?:^\w|[A-Z]|\b\w|\s+)/g, function(match, index) {
        if (+match === 0) return ""; // or if (/\s+/.test(match)) for white spaces
        return index == 0 ? match.toLowerCase() : match.toUpperCase();
    });
}

let $el = $('[data-toggle="modal"]');
if ($el.length) {
    $el.data('backdrop', 'static');
}

$('.logout-button').click(function(event) {
    event.preventDefault();

    if (typeof Intercom !== 'undefined') {
        Intercom('shutdown');
    }

    location.href = this.href;
});