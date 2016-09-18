var new_campaign_view = Backbone.View.extend({
	d:null,
	events:{
		"click #save-campaign-button": 'save_campaign'
	},
	initialize: function(){
		$('#start-date').datetimepicker({
            format: 'YYYY-MM-DD'
        });

        $('#end-date').datetimepicker({
            format: 'YYYY-MM-DD'
        });
		$('#other-date-1').datetimepicker({
            format: 'YYYY-MM-DD'
        });

        $('#other-date-2').datetimepicker({
            format: 'YYYY-MM-DD'
        });

        //cmpaign types
   //      campaign_types.forEach(function(c){
   //      	console.log(c);
   //      	var option = $('<option />').text(c.name).val(c.id);
			// $('#campaign-types').append( option );
   //      });
	},
	show_saved: function(d){
 		this.$el.append('<div class="col-md-12"><div class="alert alert-success" role="alert">Saved: ' + d.title +'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button></div></div>');
	},
	update_campaign: function(){
		var view = this;
		console.log('running update');
		console.log(view.d);
		var form_data = {
		    	title: $('#campaign-title').val(),
				description: $('#campaign-description').val(),
				start_date: $('#start-date').val(),
				end_date: $('#end-date').val(),
				goals: $('#campaign-goals').val(),
				type: $('#campaign-types option:selected').val(),
				budget: $('#campaign-budget').val()
		    };

		$.ajax({
		    url: '/campaign/edit/'+view.d.id,
		    type: 'post',
		    data: form_data,
			headers: {
            	'X-CSRF-TOKEN': $('input[name=_token]').val()
        	},
		    dataType: 'json',
		    success: function (data) {
				console.log(data);
				if(data.id){
					view.d = data;
					view.show_saved(data);
				}
			}
		});	
	},
	save_campaign: function(){
		var view = this;
		console.log('clicked save');
		console.log(view.d);
		if(!view.d){
		$.ajax({
		    url: '/campaign/create',
		    type: 'post',
		    data: {
		    	title: $('#campaign-title').val(),
				description: $('#campaign-description').val(),
				start_date: $('#start-date').val(),
				end_date: $('#end-date').val(),
				goals: $('#campaign-goals').val(),
				type: $("#campaign-types option:selected").val(),
				budget: $('#campaign-budget').val()
		    },
			headers: {
            	'X-CSRF-TOKEN': $('input[name=_token]').val()
        	},
		    dataType: 'json',
		    success: function (data) {
				console.log(data);
				if(data.id){
					view.d = data;
					view.show_saved(data);
				}
			}
		});
		}else{
			view.update_campaign();
		}
	}
});