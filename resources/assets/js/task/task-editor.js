(function($) {

    setUpDatepickers();

    var taskForm = {
        notify: $('<div class="alert alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></button><div id="task-status-text"></div></div>'),

        loader: $('<img src="/images/loading.gif" style="max-height:30px;" id="loader" />'),

        show_success(msg) {
            let $status = $('#task-status-message');
            $status.append(this.notify);
            $('#task-status-text').text(msg);
            $status.find('.alert').addClass('alert-success');
        },

        show_load() {
            $('.head-actions').prepend(this.loader);
        },

        hide_load() {
            $('#loader').remove();
        },

        update_task() {
            this.show_load();
            let formData = this.form_data();

            return $.ajax({
                method: 'post',
                url: '/task/update/' + this.task_id(),
                data:  formData,
            })
            .then(function(response) {
                if (response.success) {
                    this.hide_load();
                    this.show_success('Updated task: ' + formData.name);
                }
            }.bind(this));
        },

        task_id() {
            return $('input[name=task_id]').val();
        },

        form_data() {
            return {
                name: $('#name').val(),
                start_date: $('#start_date').val(),
                due_date: $('#due_date').val(),
                url: $('#url').val(),
                explanation: $('#explanation').val(),
                assigned_users: this.assigned_users(),
                attachments:  this.attachments(),
                _token: $('input[name=_token]').val(),
            };
        },

        assigned_users() {
            return $('#task-assignment-non-modal :checked')
                .toArray()
                .map(function(checkbox) {
                    return $(checkbox).data('id');
                });
        },

        attachments(){
            return $('input[name="files[]"]')
                .map(function() {
                    return this.value;
                }).get();
        },

        close_task() {
            var t = this;
            var form_data = {
                name: $('#name').val()
            };
            this.show_load();
            $.post('/task/close/' + $('input[name=task_id]').val(),{
                    _token: $('input[name=_token]').val()
                },function(res){
                if(res.success){
                    t.hide_load();
                    t.show_success('Closed task: ' + form_data.name);
                }
            });
        }
    };

    $(function() {
        $('#close-task').click(function() {
            taskForm.close_task();
        });
        $('#update-task').click(function() {
            taskForm.update_task();
        });

        //runs the action to submit the task
        $('#add-task-button').click(function() {
            add_task(addTaskCallback);
        });

        function addTaskCallback(task) {
            tasks.add(new task_model(task_map(task)));
            $('#addTaskModal').modal('hide');
        }
    });

    function setUpDatepickers() {
        $('#start_date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm',
            sideBySide: true
        });

        $('#due_date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm',
            sideBySide: true
        });
    }
})(jQuery);

(function () {

    $('.attachment-delete').click(function (event) {
        event.preventDefault();
        var $this = $(this);

        swal({
            title: 'Are you sure?',
            text: "You won't be able to revert this.",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!'
        }).then(function () {
            var $li = $this.parents('li');
            var attachmentId = $this.data('id');

            $.ajax({
                method: 'delete',
                url: '/api/attachments/' + attachmentId
            });

            $li.slideUp('fast'); // Optimistic feedback.
        });
    });

    var attachmentUploader = new Dropzone('#attachment-uploader', { url: '/edit/attachments' });

    attachmentUploader.on('success', function (file, response) {
        var hiddenField = $('<input/>', {
            name: 'files[]',
            type: 'hidden',
            value: response.file
        });

        hiddenField.appendTo($('.panel-container'));
    });
})();