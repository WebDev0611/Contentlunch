/* the ideas tab/page */
(function(document, $){

    //kicks off the app
    $(function(){
    	var ideas = new ideas_collection();

    	ideas.fetch({
    		success: function(res){
    			var updated_time = res.map(function(m){
    				var a = m;
    				a.created_at = a.created_at * 1000;
    				a.updated_at = a.updated_at * 1000;
    				return a;
    			});

    			ideas.reset(updated_time);
    		},
    		error: function(){
    			//console.log('ERROR RET');
    		}
    	});

    	//main collection - view
    	var ic = new idea_container_view({ 
    		el:'#idea-container',
    		collection: ideas 
    	});

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
    });


})(window.document, jQuery);