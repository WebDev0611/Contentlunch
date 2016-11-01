/* the ideas editor JS */
(function(document, $){
	//view for the editor
	var idea_editor_view = Backbone.View.extend({
	    events: {
	        "click .save-idea": 'save_idea',
	        "click .reject-idea": 'reject_idea',
	        "click .park-idea": 'park_idea'
	    },
	    initialize: function(){
	    	console.log(this.model.attributes);
	    },
	    render: function(){},
	    save_idea: function(){
	    	var v = this;
	        console.log('clicked save');
	        var form_obj = {
		        name: $('#idea-name').val(),
		        idea: $('#idea-text').val(),
		        tags: $('#idea-tags').val(),


	    	};
	    	return $.ajax({
	    		url: '/idea/update/' + v.model.get('id'),
	    		data: form_obj,
	    		type:'post',
	    		headers: {
	            	'X-CSRF-TOKEN': $('input[name=_token]').val()
	        	}
	    		})
	    		.then(function(res){
	    			console.log(res);
	    			v.show_alert(true, 'Successfully saved the idea: ' + res.name);
	    		})

	    },
	    reject_idea: function(){
	    	var v = this;
	        console.log('clicked park');
        	return $.ajax({
        		url: '/idea/reject/' + v.model.get('id'),
        		type:'post',
        		headers: {
                	'X-CSRF-TOKEN': $('input[name=_token]').val()
            	}
        		})
        		.then(function(res){
        			console.log(res);
        			v.show_alert(true, 'Idea has been rejected!');
        		})
	    },
	    park_idea: function(){
	    	var v = this;
	        console.log('clicked park');
        	return $.ajax({
        		url: '/idea/park',
        		type:'post',
        		data:{idea_id: v.model.get('id')},
        		headers: {
                	'X-CSRF-TOKEN': $('input[name=_token]').val()
            	}
        		})
        		.then(function(res){
        			console.log(res);
        			v.show_alert(true,'Idea has been parked!');
        		})
	    },
	    show_alert: function(status,text){
	    	console.log('in show alert()');
	    	var alert_button =  $('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
	    	var alert_text = $('<div class="alert alert-success alert-dismissable" role="alert" />').text(text).append(alert_button);
	    	$('#responses').append( alert_text );
	    }
	});

	var idea = new idea_model(idea_obj);
	var idea_editor = new idea_editor_view({el: '#idea-editor',model: idea});

})(window.document, jQuery);