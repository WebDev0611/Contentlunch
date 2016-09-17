var new_campaign_view = Backbone.View.extend({
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
        campaign_types.forEach(function(c){
        	console.log(c);
        	var option = $('<option />').text(c.name).val(c.id);
			$('#campaign-types').append( option );
        });
	},
	save_campaign: function(){
		console.log('clicked save');
		$.ajax({
		    url: '/campaign/create',
		    type: 'post',
		    data: {
		    	title: $('#campaign-title').val(),
				description: $('#campaign-description').val(),
				start_date: $('#start-date').val(),
				end_date: $('#end-date').val(),
				goals: $('#campaign-goals').val(),
				type: $('#campaign-type').val()
		    },
			headers: {
            	'X-CSRF-TOKEN': $('input[name=_token]').val()
        	},
		    dataType: 'json',
		    success: function (data) {
				console.log(data);
			}
		});
	}
});