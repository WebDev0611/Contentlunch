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
        }
    });

    $('#contentType').change(updateCount);

    $('.datetimepicker').datetimepicker({
        format: 'YYYY-MM-DD'
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
        var TWEET_CONTENT_TYPE = "17";
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
        }
    }

    /**
     * Content task handling
     */
    var tasks = new task_collection();

    tasks.on('add', function(task) {
        var element = new ContentTaskView({ model: task });
        element.render();
        $('.content-tasks-box-container').append(element.el);
    });

    fetchTasks();

    function fetchTasks() {
        return $.ajax({
            url: '/api/contents/' + contentId() + '/tasks',
            data: { open: '1' },
            method: 'get',
        })
        .then(function(response) {
            tasks.add(response.map(function(task) {
                return new task_model(task);
            }));
        });
    }

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
});