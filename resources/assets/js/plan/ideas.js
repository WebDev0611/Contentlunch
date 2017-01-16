/* the ideas tab/page */
(function(document, $) {

    var ideas = new ideas_collection();

    //main collection - view
    var ic = new idea_container_view({
        el: '#idea-container',
        collection: ideas
    });

    ideas.fetch().then(response => ideas.reset(response));

    $('#parked-ideas-link').click(function(){
        ic.render('parked');
        $(this).parent().addClass('active');
        $('#active-ideas-link').parent().removeClass('active');
    });

    $('#active-ideas-link').click(function(){
        ic.render('active');
        $(this).parent().addClass('active');
        $('#parked-ideas-link').parent().removeClass('active');
    });

    //tasks
    $('#add-task-button').click(function() {
        add_task(addTaskCallback);
    });

    function addTaskCallback(task) {
        $('#addTaskModal').modal('hide');
    }

})(window.document, jQuery);