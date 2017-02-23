var camelize = function(str) {
    return str.replace(/(?:^\w|[A-Z]|\b\w|\s+)/g, function(match, index) {
        if (+match === 0) return ""; // or if (/\s+/.test(match)) for white spaces
        return index == 0 ? match.toLowerCase() : match.toUpperCase();
    });
};


(function($) {

	var topic_result = Backbone.Model.extend({
		defaults:{
			keyword: '',
			volume: 0,
			timestamp: new Date().getTime()
		}
	});

	var result_view = Backbone.View.extend({
		className: "col-md-6",
		tagName: "div",
		events: {
			"click input": "select_content"
		},
		template: _.template('<label for="<%= camelize(keyword) %>" class="checkbox-tag"><input id="<%= camelize(keyword) %>" type="checkbox"><span><%= keyword %></span></label>'),
		initialize: function(){
			//console.log('init!');
			//console.log(this.model.attributes);
			this.listenTo(this.model, "remove", this.removeFromDOM);
		},
		render: function(){
			this.$el.html( this.template(this.model.attributes) );
			return this;
		},
		removeFromDOM: function(){
			var that = this;
			this.$el.fadeOut(200,function(){
				that.$el.remove();
			});
		},
		select_content: function(){
			if( !this.model.get('selected') ){
				this.model.set('selected',true);
			}else{
				this.model.set('selected',false);
			}
		}
	});

	var long_tail_collection = Backbone.Collection.extend({
		model: topic_result
	});
	var short_tail_collection = Backbone.Collection.extend({
		model: topic_result
	});


	/* idea JS for the modal */
	var selected_content = Backbone.Collection.extend({
		model: topic_result
	});
	var selected_content_view = Backbone.View.extend({
		template: _.template( $('#selected-topic-template').html() ),
		initialize: function(){
			this.render();
		},
		render: function(){
			//console.log(this.model.attributes );
			this.$el.html( this.template( this.model.attributes ) );
		}
	});

	var idea_model = Backbone.Model.extend({
		defaults:{
			name: "IDEA 1",
			body: "This is where the idea text goes",
			tags: [],
			collaborators: [],
			content: new selected_content()
		}
	});

	var create_idea_view = Backbone.View.extend({
		events: {
			"click .save-idea": "save",
			"click .park-idea": "park"
		},

		initialize(){
			this.listenTo(this.model.attributes.content,'update',this.render);
			this.render();
		},

		render() {
			var view = this;

			view.$el.find('#selected-content').html('');
			view.model.attributes.content.each(function(m){
				var sel_content = new selected_content_view({model:m});
				view.$el.find('#selected-content').append( sel_content.$el );
			});

			if (view.model.attributes.content.length < 1) {
				view.$el.find('.form-delimiter').hide();
			} else {
				view.$el.find('.form-delimiter').show();
			}
		},
		park(){
			this.store('parked');
		},

		save() {
			this.store('active');
		},

		store(status) {
			var view = this;
			$('.park-idea').prop('disabled',true);
			$('.save-idea').prop('disabled',true);
			$('#idea-status-alert').addClass('hidden');
			if( $('.idea-name').val().length < 1 ){
				view.show_error('Idea title required');
				return;
			}
			var loadingIMG = $('<img src="/images/loading.gif" style="max-height:30px;" />');
			console.log('loading image here');
			$('#idea-menu').prepend(loadingIMG);
			//saves the form data
			var content = this.model.attributes.content;
			var idea_obj = {
				name: $('.idea-name').val(),
				idea: $('.idea-text').val(),
				tags: $('.idea-tags').val(),
				status: status || 'active',
				content: content.map(function(m){
					return m.attributes;
				})
			};

			$.ajax({
			    url: '/ideas',
			    type: 'post',
			    data: idea_obj,
			    dataType: 'json',
			    success: function (data) {
					$(loadingIMG).remove();
					view.hide_modal();
					view.clear_form();
					view.render();
				}
			});
		},
		hide_modal: function(){
			this.$el.modal('hide');
		},
		show_error: function(msg){
			$('#idea-status-alert')
				.toggleClass('hidden')
				.toggleClass('alert-danger')
				.show();

			$('#idea-status-text').text(msg);
			$('.park-idea').prop('disabled',false);
			$('.save-idea').prop('disabled',false);
		},
		clear_form: function(){
			var view = this;
			this.model.attributes.content.each(function(m){
				if(m){
					m.set('selected',false);
					view.model.attributes.content.remove(m);
				}
			});
			$('.idea-name').val('');
			$('.idea-text').val('');
			$('.idea-tags').val('');
			$('.park-idea').prop('disabled',false);
			$('.save-idea').prop('disabled',false);
		}
	});

	var new_idea = new idea_model();
	var idea_form = new create_idea_view({el: '#createIdea',model: new_idea});


	/* main page event setup */
	$(function(){
		var long_tail_results = new long_tail_collection();
		var short_tail_results = new short_tail_collection();

		var sf = function(r){
			if( r.keyword.split(' ').length <= 3 ){
				return true;
			}else{
				return false;
			}
		};
		var lf = function(r) {
			if( r.keyword.split(' ').length >= 2 ){
				return true;
			}else{
				return false;
			}
		};

		var v_sort = function(a,b) {
			return b.volume - a.volume;
		};

		var map_result = function(m){
			return {keyword: m.keyword,volume: m.volume};
		};

		var get_topic_data = function(term = '') {
			var search_obj = {};

			if (term.length > 3) {
				var ldIMG = $('<img src="/images/loading.gif" style="max-height:30px;" />');

				$('span.input-form-button-action').append(ldIMG);
				search_obj.keyword = term;


				$.getJSON('/topics', search_obj)
					.then(res => {
						$('span.input-form-button-action img').remove();

						var lt = res.results.filter(lf).map(map_result).sort(v_sort);
						var st = res.results.filter(sf).map(map_result).sort(v_sort);

						short_tail_results.add(st);
						long_tail_results.add(lt);
					})
					.catch(response => {
						$('span.input-form-button-action img').remove();
						swal('Error!', response.responseJSON.data, 'error');
					});
			}
		};

        const topicSearch = () => {
            long_tail_results.remove(long_tail_results.models);
            short_tail_results.remove(short_tail_results.models);
            get_topic_data($('#topic-search-val').val());
        }

        const topicInputKeyUpHandler = event => {
            const keyCode = event.keyCode;
            const ENTER_KEY = 13;
            const value = $('#topic-search-val').val();

            if (keyCode === ENTER_KEY && value) {
                topicSearch();
            }
        }

        $('#topic-search').click(topicSearch);
        $('#topic-search-val').keyup(topicInputKeyUpHandler);

		long_tail_results.on('add',function(m){
			var result = new result_view({model: m});
			result.render();
			result.$el.hide();
			result.$el.fadeIn();
			$('#long-tail-results').append( result.el );
		});
		long_tail_results.on('change', function(l){
			if(l.get('selected')){
				new_idea.attributes.content.add(l);
			}else{
				new_idea.attributes.content.remove(l);
			}
		});


		short_tail_results.on('add',function(m){
			var result = new result_view({model: m});
			result.render();
			result.$el.hide();
			result.$el.fadeIn();
			$('#short-tail-results').append( result.el );
		});
		short_tail_results.on('change', function(l){
			if(l.get('selected')){
				new_idea.attributes.content.add(l);
			}else{
				new_idea.attributes.content.remove(l);
			}
		});

		//get_topic_data();

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