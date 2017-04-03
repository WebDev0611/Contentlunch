// Calendar helpers
function get_this_calendar() {
    let this_calendar_arr = $.grep(my, function (e) {
        return e.id == calendar.id;
    });
    return this_calendar_arr[0];
}

function add_calendar(callback) {

    $('.new-calendar-error').remove();
    if (isCalendarDataValid()) {
        $('#add-calendar-button').prepend(loadIMG);

        return $.ajax({
            url: '/calendar/add',
            type: 'post',
            data: getCalendarData(),
            dataType: 'json',
        })
            .then(addedCalendarCallback(callback))
            .catch(showErrorFeedback);
    }
};

function isCalendarDataValid() {
    let data = getCalendarData();

    let name_ok = data.name.length > 2;
    let content_ok = data.content_type_ids.length > 0;

    let modalContainer = $("#createCalendarModal .sidemodal-container");
    let errorShown = false;

    if (!name_ok) {
        modalContainer.append($('<p>Calendar name has to be at least 3 characters long</p>').attr({
            class: 'new-calendar-error text-danger'
        }));
        errorShown = true;
    }
    if(data.show_tasks == null && data.show_ideas == null && !content_ok){
        modalContainer.append($('<p>At least 1 content type has to be selected</p>').attr({
            class: 'new-calendar-error text-danger'
        }));
        errorShown = true;
    }

    return !errorShown;
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

        window.location = calendarRedirectUrl(res.id);

        if ('function' === typeof callback) {
            callback();
        }
    }
}

function calendarRedirectUrl(calendarId) {
    let redirectUrl = '/calendar';

    if (window.location.pathname.indexOf('weekly') >= 0) {
        redirectUrl = '/weekly';
    }
    if (window.location.pathname.indexOf('daily') >= 0) {
        redirectUrl = '/daily';
    }

    return redirectUrl + '/' + calendarId;
}

function showErrorFeedback(response) {
    $(loadIMG).remove();
    if (response.status === 403) {
        showUpgradeAlert(response.responseJSON.data);
    } else {
        swal('Error!', response.responseJSON.data, 'error');
    }
}

function getCalendarId() {
    let element = $('#is_calendar_item');
    let calendarId = null;

    if (element.length && element.val() === 'on') {
        calendarId = element.data('id');
    }

    return calendarId;
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

// Idea
function store_idea(action, callback) {
    $('.save-idea').prop('disabled', true);
    $('.park-idea').prop('disabled', true);

    $('#idea-status-alert').addClass('hidden');
    if ($('.idea-name').val().length < 1) {
        $('#idea-status-alert')
            .toggleClass('hidden')
            .toggleClass('alert-danger')
            .show();

        $('#idea-status-text').text('Idea title required');
        $('.save-idea').prop('disabled', false);
        $('.park-idea').prop('disabled', false);

        return;
    }

    $('#idea-menu').prepend(loadIMG);

    //saves the form data
    //let content = this.collection.where({selected: true});
    let idea_obj = {
        name: $('.idea-name').val(),
        idea: $('.idea-text').val(),
        tags: $('.idea-tags').val(),
        created_at: $('#idea_date').val(),
        calendar_id: getCalendarId(),
        status: action,
        /*
         content: content.map(function (m) {
         return m.attributes;
         })
         */
    };
    return $.ajax({
        url: '/ideas',
        type: 'post',
        data: idea_obj,
        headers: getCSRFHeader(),
        dataType: 'json',
        success: addedIdeaCallback(callback)
    }).catch(showErrorFeedback);
}

function clearIdeaInputs() {
    $('.idea-name').val('');
    $('.idea-text').val('');
    $('.idea-tags').val('');
}

function addedIdeaCallback(callback) {
    return function (res) {
        $(loadIMG).remove();
        $("#createIdea").modal('hide');
        clearIdeaInputs();
        $('.save-idea').prop('disabled', false);
        $('.park-idea').prop('disabled', false);

        if ('function' === typeof callback) {
            callback();
        }
    }
}

// Content
function store_content(callback) {
    $('#add-content-button').prop('disabled', true);
    let contentTitle = $('#content-title').val();
    let contentType = $("#content-type-id option:selected").val();

    $('#content-status-alert').addClass('hidden');
    $('#content-status-text').text('');

    if (contentTitle.length < 1 || contentType.length < 1) {
        $('#content-status-alert')
            .toggleClass('hidden')
            .show();

        if (contentTitle.length < 1) {
            $('#content-status-text').text('Content title required');
        } else if (contentType.length < 1) {
            $('#content-status-text').text('Content type required');
        }

        $('#add-content-button').prop('disabled', false);

        return;
    }

    $('#content-menu').prepend(loadIMG);

    //saves the form data
    let content_obj = {
        title: $('#content-title').val(),
        content_type_id: $("#content-type-id option:selected").val(),
        due_date: $('#content-due-date').val(),
        calendar_id: getCalendarId(),
        created_at: $('#content_date').val()
    };
    return $.ajax({
        url: '/create/new',
        type: 'post',
        data: content_obj,
        headers: getCSRFHeader(),
        dataType: 'json',
        success: addedContentCallback(callback),
        error: function (xhr, ajaxOptions, thrownError) {
            $(loadIMG).remove();

            let errorMsg = '';
            let responseObj = JSON.parse(xhr.responseText);
            $.each(responseObj, function( index, value ) {
                errorMsg += value;
            });

            $('#content-status-alert')
                .toggleClass('hidden')
                .show();
            $('#content-status-text').text(errorMsg);
            $('#add-content-button').prop('disabled', false);
        }
    });
}

function addedContentCallback(callback) {
    return function (res) {
        $(loadIMG).remove();
        $("#addContentModal").modal('hide');
        clearContentInputs();
        $('#add-content-button').prop('disabled', false);

        if ('function' === typeof callback) {
            callback();
        }
    }
}

function clearContentInputs() {
    $('#content-title').val('');
    $('#content-due-date').val('');
}


// Calendar: Invite guests
function send_invites() {
    $('#invite-guests .alert').hide();
    $('#invite-guests-button').prepend(loadIMG);
    let emails = $('#invite-guests .email-invites').val();

    if (!emails) {
        console.log('no');
        $('#invite-guests .alert').slideDown('fast');
        $(loadIMG).remove();
        return;
    }

    let emailsCount = emails.split(',').length;

    return $.ajax({
        headers: getCSRFHeader(),
        method: 'post',
        url: '/invite/emails',
        data: {
            emails: emails
        },
        success: function (result) {
            show_feedback(true, emailsCount)
        },
        error: function (result) {
            show_feedback(false)
        },
        complete: function () {
            $(loadIMG).remove()
        }
    });
}

function show_feedback(ok, emailsCount = 0) {
    const alert_ok = _.template(`
            <div class='alert alert-success alert-forms' id='dashboard-feedback' style='display:none'>
                <%= emailsCount > 1 ? 'Invites' : 'Invite' %> sent!
            </div>
        `);
    const alert_error = _.template(`
            <div class='alert alert-danger alert-forms' id='dashboard-feedback' style='display:none'>
                There was an issue with provided email adress(es)
            </div>
        `);

    const element = ok ? alert_ok({emailsCount: emailsCount}) : alert_error({});

    $('#dashboard-feedback').remove();
    $(element).prependTo('#invite-guests');
    $('#dashboard-feedback').slideDown();

    setTimeout(function () {
        $('#dashboard-feedback').slideUp();
    }, 3000);
}

// Filter
function reset_filter() {
    let calendar = get_this_calendar();

    $('#filter-type-tasks, #filter-type-ideas, .filter-content-type option').removeAttr('selected');
    if (calendar.show_tasks == "1") {
        $('#filter-type-tasks').attr('selected', 'selected');
    }
    if (calendar.show_ideas == "1") {
        $('#filter-type-ideas').attr('selected', 'selected');
    }
    $.each(calendar.content_types, function (key, type) {
        if (type.active == '1') {
            $('#filter-type-id-' + type.id).attr('selected', 'selected');
        }
    });
}

function get_filtered_calendar(multiple_select) {
    let new_calendar = {
        'show_tasks': 0,
        'show_ideas': 0,
        'content_types': []
    };

    $.each(multiple_select.find(":selected"), function (key, item) {
        switch (item.value) {
            case 'tasks':
                new_calendar.show_tasks = '1';
                break;
            case 'ideas':
                new_calendar.show_ideas = '1';
                break;
            default:
                new_calendar.content_types.push({'id': item.value, 'name': item.text});
                break;
        }
    });

    return new_calendar;
}