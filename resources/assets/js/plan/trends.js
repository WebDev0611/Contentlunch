var camelize = function(str) {
return str.replace(/(?:^\w|[A-Z]|\b\w|\s+)/g, function(match, index) {
if (+match === 0) return ""; // or if (/\s+/.test(match)) for white spaces
return index == 0 ? match.toLowerCase() : match.toUpperCase();
});
};


(function($){
	var trend_api_host = '/trending';

	var create_message_view = Backbone.View.extend({
		initialize: function(){
			this.listenTo(this.collection,"update",this.render);
			this.listenTo(this.collection,"change",this.render);
		},
		render: function(){
			var selected = this.collection.filter(function(m){
				return m.get('selected');
			});

			if(selected.length > 0 ){
				this.$el.html('Create an idea from ' + selected.length + ' selected items');
			}else{
				this.$el.html('');
			}
		}
	});

	var create_idea_cont_view = Backbone.View.extend({
		events:{
			"click": "unselect"
		},
		template: _.template( $('#selected-trend-template').html() ),
		initialize: function(){
			this.render();
		},
		render: function(){
			this.$el.html(this.template( this.model.attributes ) );
			return this;
		},
		unselect: function(){
			this.model.set('selected',false);
			this.$el.toggleClass('tombstone-active');
		}
	});

	var create_idea_modal = Backbone.View.extend({
		events:{
			"click .save-idea": "save",
			"click .park-idea": "park"
		},
		initialize:function(){
			this.listenTo(this.collection, "update", this.render);
			this.listenTo(this.collection, "change", this.render);
		},
		render:function(){
			var view = this;
			var selected = this.collection.where({selected: true});
			view.$el.find('#selected-content').html('');

			selected.forEach(function(m){
				var sel_cont_view = new create_idea_cont_view({model: m});
				view.$el.find('#selected-content').append( sel_cont_view.el );
			});
			this.$el.find('.sidemodal-header-title').text('Create an idea from ' + selected.length + ' selected items');
			if( selected.length < 1 ){
				this.$el.find('.form-delimiter').hide();
			}else{
				this.$el.find('.form-delimiter').show();
			}
		},
		hide_modal: function(){
			this.$el.modal('hide');
		},
		clear_form: function(){
			 $('.idea-name').val('');
			 $('.idea-text').val('');
		     $('.idea-tags').val('');
		     this.collection.each(function(m){
		     	m.set('selected',false);
		     });
			$('.save-idea').prop('disabled',false);
			$('.park-idea').prop('disabled',false);
		},
		save: function(){
			this.store('active');
		},
		park: function(){
			this.store('parked');
		},
		show_error: function(msg){
			$('#idea-status-alert')
				.toggleClass('hidden')
				.toggleClass('alert-danger')
				.show();

			$('#idea-status-text').text(msg);
			$('.save-idea').prop('disabled',false);
			$('.park-idea').prop('disabled',false);
		},
		store: function(action){
			var view = this;

			$('.save-idea').prop('disabled',true);
			$('.park-idea').prop('disabled',true);

			$('#idea-status-alert').addClass('hidden');
			if( $('.idea-name').val().length < 1 ){
				view.show_error('Idea title required');
				return;
			}
			var loadingIMG = $('<img src="/images/loading.gif" style="max-height:30px;" />');
			console.log('loading image here');
			$('#idea-menu').prepend(loadingIMG);
			//saves the form data
			var content = this.collection.where({ selected: true });
			var idea_obj = {
				name: $('.idea-name').val(),
				idea: $('.idea-text').val(),
				tags: $('.idea-tags').val(),
				status: action,
				content: content.map(function(m){
					return m.attributes;
				})
			};
			$.ajax({
			    url: '/ideas',
			    type: 'post',
			    data: idea_obj,
				headers: {
	            	'X-CSRF-TOKEN': $('input[name=_token]').val()
	        	},
			    dataType: 'json',
			    success: function (data) {
					$(loadingIMG).remove();
					view.hide_modal();
					view.clear_form();
				}
			});
		}
	});

	
	$(function(){

		$('#trend-search').click(function(){
			var search_val = $('#trend-search-input').val() || "";
			get_trending_topics(search_val);
		});

		var results = new trend_result_collection();

		var get_trending_topics = function(topic){
			topic = topic || '';
			var loadingGIF = $('<img src="/images/loading.gif" style="max-height:30px" />');
			$(loadingGIF).insertAfter('#trend-search');

			$.getJSON(trend_api_host,{topic: topic},function(res){
				var trends_result = res.articles;
				$(loadingGIF).remove();
				var format_share_res = function(shares){
					//check if less than 1k
					if(shares < 1000){
						return shares;
					}else{
						return Math.floor(shares/1000) + 'K'
					}
				};

				var new_trends = trends_result.map(function(t){
					
					var trend_obj = {
						"title": t.title,
						"image": t.image_url,
						"body": t.excerpt,
						"when": format_time_ago(t.earliest_known_date),
						"source": t.source,
						"link":t.url,
						"author":'',
						"total_shares": format_share_res( t.share_count ),
						"fb_shares":0,
						"tw_shares": 0,
						"google_shares": 0,
						"video": 0
					};

					return trend_obj;
				});

				results.remove( results.models );
				results.add(new_trends);
				return new_trends;
			},
			function(error){
				console.log(error)
				console.log('couldnt connect to the endpoint/error');
			});
		};

		new create_message_view({el:"#create-alert", collection: results });
		var create_idea = new create_idea_modal({el: '#createIdea', collection: results });

		//get_trending_topics();

		new trend_results_view({el: '#trend-results', collection: results});


		//task method
        //runs the action to submit the task
        $('#add-task-button').click(function() {
            add_task(addTaskCallback);
        });

        function addTaskCallback(task) {
            $('#addTaskModal').modal('hide');
        }
	});

})(jQuery);

