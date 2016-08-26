var camelize = function(str) {
return str.replace(/(?:^\w|[A-Z]|\b\w|\s+)/g, function(match, index) {
if (+match === 0) return ""; // or if (/\s+/.test(match)) for white spaces
return index == 0 ? match.toLowerCase() : match.toUpperCase();
});
};


(function($){

	var topic_result = Backbone.Model.extend({
		defaults:{
			keyword: '',
			volume: 0,
			timestamp: new Date().getTime()
		}
	});

	//place holder data
	var dummy_data_long = [
		{keyword: "Diesel Engines"},
		{keyword: "Gasoline Engines"},
		{keyword: "Auto Service"},
		{keyword: "Diesel Trucks"},
		{keyword: "Turbo Diesel"},
	];
	
	var dummy_data_short = [
		{keyword: "Diesel Engine Maintenance"},
		{keyword: "Gasoline Engines Mileage"},
		{keyword: "Auto Service in California"},
		{keyword: "Most Reliable Diesel Trucks"},
		{keyword: "Turbo Diesel Efficiency"},
	];

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
			console.log('REMOVED!!');
			var that = this;
			this.$el.fadeOut(200,function(){
				that.$el.remove();
			});
		},
		select_content: function(){
			console.log('content clicked!');
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
		intialize: function(){
			console.log(' new sel content view!');
			this.el = this.template( this.model.attributes );
		},
		render: function(){
			return this;
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
		events:{
			"click .save-idea": "save",
			"click .park-idea": "park"
		},
		initialize: function(){
			this.listenTo(this.model.attributes.content,'update',this.render);
			this.render();
		},
		render:function(){
			var that = this;
			console.log('rendering');
			this.model.attributes.content.each(function(m){
				var sel_content = new selected_content_view({model:m});
				that.$el.find('#selected-content').append( sel_content );
			});
			console.log(this.model.attributes);
		},
		save: function(){
			console.log('clicked save');
			console.log(this.model.attributes);
		}
	});

	var new_idea = new idea_model();
	var idea_form = new create_idea_view({el: '#createIdea',model: new_idea});




	/* main page event setup */
	$(function(){
		var long_tail_results = new long_tail_collection();
		var short_tail_results = new short_tail_collection();

		var map_result = function(m){
			return {keyword: m.keyword};
		};

		$('#topic-search').click(function(){
			console.log('search clicked!');
			long_tail_results.remove( long_tail_results.models );
			short_tail_results.remove( short_tail_results.models );


			$.getJSON('/topics',{keyword: $('#topic-search-val').val(),terms:'short' },function(res){
				console.log("back from endpoint");
				console.log("::");
				console.log(res);

				var topic_objs = res.results.map(map_result).sort(function(a,b){
					return b.volume - a.volume;
				});

				short_tail_results.add(topic_objs);
			});

			$.getJSON('/topics',{keyword: $('#topic-search-val').val(),terms: 'long' },function(res){
				console.log("back from endpoint");
				console.log("::");
				console.log(res);

				var topic_objs = res.results.map(map_result).sort(function(a,b){
					return b.volume - a.volume;
				});
				
				long_tail_results.add(topic_objs);
			});
			//setTimeout(function(){
			//	short_tail_results.add(dummy_data_short);
			//	long_tail_results.add(dummy_data_long);
			//},500);

		});

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
			console.log(new_idea.attributes.content.toJSON() );
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

		// long_tail_results.add(dummy_data_long);
		// short_tail_results.add(dummy_data_short);
		var get_topic_data = function(term = ''){
			var short_obj = {terms: 'short'};
			var long_obj = {terms: 'long'};

			if(term.length > 2){
				short_obj.keyword = term;
				long_obj.keyword = term;
			}

			$.getJSON('/topics',short_obj,function(res){
				var topic_objs = res.results.map(map_result).sort(function(a,b){
					return b.volume - a.volume;
				});
				short_tail_results.add(topic_objs);
			});

			$.getJSON('/topics',long_obj,function(res){
				var topic_objs = res.results.map(map_result).sort(function(a,b){
					return b.volume - a.volume;
				});
				
				long_tail_results.add(topic_objs);
			});
		};
		get_topic_data();
	});

})(jQuery);