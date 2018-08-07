'use strict';

$(function() {

    var contentEditor;

    var characterCounter = new CharacterCounterView({ el: '.character-counter' });

    characterCounter.hide();

    tinymce.init({
        selector: 'textarea.wysiwyg',  // change this value according to your HTML
        plugin: 'a_tinymce_plugin',
        a_plugin_option: true,
        a_configuration_option: 400,
        setup: function(editor) {
            contentEditor = editor;
            editor.on('keyup', updateCount);
        },
        plugins: ["advlist autolink lists link image charmap print preview anchor help", "searchreplace visualblocks fullscreen", "insertdatetime media table contextmenu imagetools"],
        toolbar: "insertfile undo redo | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist | link image | help",
        menubar: 1,
        statusbar: 1,
        branding: false
    });

    $('#contentType').change(updateCount);

    $('.datetimepicker').datetimepicker({
        format: 'MM/DD/YYYY'
    });

    $('.selectpicker').selectpicker({
        style : 'btn-white',
        size: 10
    });

    $('.changes').hide();
    $(".showChanges").on('click', function(){
        var $this = $(this),
            divClass = $this.attr('data-class');

        $("." + divClass).toggle();
   });

    $('form').submit(function(event) {
        var MAX_TWEET_CHARACTERS = 140;
        var selectedContentType = $('#contentType').val();

        if (selectedContentType == TWEET_CONTENT_TYPE &&
            characterCounter.characters >= MAX_TWEET_CHARACTERS)
        {
            $('#twitterError').slideDown('fast');
            event.preventDefault();
        }
    });

    function updateCount(event) {
        if (characterCounter.isTweet()) {
            characterCounter.show();
            characterCounter.update(contentEditor.getContent());
        } else {
            characterCounter.hide();
        }
    }

    /**
     * Content task handling
     */
    let tasks = new task_collection();

    tasks.on('add', function(task) {
        let element = new ContentTaskView({ model: task });
        element.render();
        $('.content-tasks-box-container').append(element.el);
    });

    tasks.populateList(contentId(), false);

    function contentId() {
        return $('input[name=content_id]').val();
    }

    $('#add-task-button').click(function() {
        add_task(addTaskCallback);
    });

    function addTaskCallback(task) {
        tasks.add(new task_model(task));
        $('#addTaskModal').modal('hide');
    }

    $('.attachment-delete, .image-delete').click(function(event) {
        event.preventDefault();
        let $this = $(this);

        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then(function () {
            let $li = $this.parents('li');
            let attachmentId = $this.data('id');

            $.ajax({
                method: 'delete',
                url: `/api/attachments/${attachmentId}`,
            });

            $li.slideUp('fast'); // Optimistic feedback.
        });

    });

    /**
     * Content destinations
     */
    var mailchimp_lists = [];
    let connection_data = {};

    $("#connections").change(function() {
        loadMailchimpDestinationData();
    });

    loadMailchimpDestinationData();

    function loadMailchimpDestinationData(){
        let destination_id = $("#connections").find(":selected").val();
        connection_data = getSelectedConnection();

        if(destination_id.length && connection_data.provider.slug === 'mailchimp') {
            // If connection is Mailchimp, fetch needed data from their API and populate corresponding fields
            $('#mailchimp_settings_row, #mailchimp_loading').removeClass('hidden');

            $.when(getMailchimpLists(destination_id)).done(function (mailchimp_lists_tmp) {
                mailchimp_lists = mailchimp_lists_tmp;
                $('#mailchimp_loading').addClass('hidden');
                populateMailchimpValues();
            });
        }
        else {
            $('#mailchimp_settings_row').addClass('hidden');
        }
    }

    function getSelectedConnection() {
        let destination_id = $("#connections").find(":selected").val();

        return $.grep(connections_details, function (connection) {
            return connection.id == destination_id;
        })[0];
    }

    function populateMailchimpValues(){
        // Clear all values
        $('#mailchimp_list').empty();
        $('#mailchimp_from_name, #mailchimp_reply_to').val('');

        // Populate lists select
        $.each(mailchimp_lists,function(key, list) {
            $('#mailchimp_list').append('<option value=' + list.id + '>' + list.name + '</option>');
        });

        if(mailchimp_settings.list && mailchimp_settings.list.length !== 0) {
            $('#mailchimp_list').find('option[value=' + mailchimp_settings.list + ']').attr('selected','selected');
        }

        if(mailchimp_settings.from_name && mailchimp_settings.from_name.length !== 0) {
            $('#mailchimp_from_name').val(mailchimp_settings.from_name);
        } else {
            $('#mailchimp_from_name').val(mailchimp_lists[0].campaign_defaults.from_name);
        }

        if(mailchimp_settings.reply_to && mailchimp_settings.reply_to.length !== 0) {
            $('#mailchimp_reply_to').val(mailchimp_settings.reply_to);
        } else {
            $('#mailchimp_reply_to').val(mailchimp_lists[0].campaign_defaults.from_email);
        }
    }
    
    function getMailchimpLists(destination_id) {
        return $.ajax({
            method: 'get',
            url: '/mailchimp/' + destination_id + '/lists',
            headers: getCSRFHeader(),
        });
    }

    $("#mailchimp_list").change(function() {
        let list = $.grep(mailchimp_lists, function (mc_list) {
            return mc_list.id == $("#mailchimp_list").find(":selected").val();
        })[0];

        $('#mailchimp_from_name').val(list.campaign_defaults.from_name);
        $('#mailchimp_reply_to').val(list.campaign_defaults.from_email);
    });
});