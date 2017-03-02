// Calendar helpers
function get_this_calendar() {
    let this_calendar_arr = $.grep(my, function (e) {
        return e.id == calendar.id;
    });
    return this_calendar_arr[0];
}

function add_calendar(callback) {

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

    $('#content-status-alert').addClass('hidden');
    if ($('#content-title').val().length < 1 || $("#content-type-id option:selected").val().length < 1) {
        $('#content-status-alert')
            .toggleClass('hidden')
            .toggleClass('alert-danger')
            .show();

        $('#content-status-text').text('Content title required \r\n Content type required');
        $('#add-content-button').prop('disabled', false);

        return;
    }

    $('#content-menu').prepend(loadIMG);

    //saves the form data
    let content_obj = {
        title: $('#content-title').val(),
        content_type: $("#content-type-id option:selected").val(),
        due_date: $('#content-due-date').val(),
        body: tinyMCE.get('content-body').getContent(),
        created_at: $('#content_date').val()
    };
    return $.ajax({
        url: '/create/new',
        type: 'post',
        data: content_obj,
        headers: getCSRFHeader(),
        dataType: 'json',
        success: addedContentCallback(callback)
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
    tinyMCE.get('content-body').setContent('');
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