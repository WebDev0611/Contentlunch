(function($){
    var taskForm = {
        notify: $('<div class="alert alert-dismissible" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">&times;</span></button><div id="task-status-text"></div></div>'),

        loader: $('<img src="/images/loading.gif" style="max-height:30px;" id="loader" />'),
        show_success: function(msg){
            $('#task-status-message').append(this.notify);
            $('#task-status-text').text(msg);
            $('#task-status-message').find('.alert').addClass('alert-success');
        },
        show_load: function(){
            $('.head-actions').prepend(this.loader);
        },
        hide_load: function(){
            $('#loader').remove();
        },
        update_task: function(){
            var t = this;
            var form_data = {
                name: $('#name').val(),
                start_date: $('#start_date').val(),
                due_date: $('#due_date').val(),
                explanation: $('#explanation').val(),
                _token: $('input[name=_token]').val(),
            };
            this.show_load();
            $.post('/task/update/' + $('input[name=task_id]').val(),form_data,function(res){
                console.log(res);
                if(res.success){
                    t.hide_load();
                    t.show_success('Updated task: ' + form_data.name);
                }
            });
        },
        close_task: function(){
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

    $(function(){
        $('#close-task').click(function(){
            taskForm.close_task();
        });
        $('#update-task').click(function(){
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
})(jQuery);