var camelize = function(str) {
return str.replace(/(?:^\w|[A-Z]|\b\w|\s+)/g, function(match, index) {
if (+match === 0) return ""; // or if (/\s+/.test(match)) for white spaces
return index == 0 ? match.toLowerCase() : match.toUpperCase();
});
};


(function($){
	var trend_api_host = '/trending';
	var trend_result = Backbone.Model.extend({
		defaults:{
			selected: false
		}
	});

	//place holder data
	var dummy_data = [
		{
			title: "Google self-driving car is tested on California highways",
			image: "http://i.imgur.com/MYB6HjU.jpg",
			body: "Visitors to Eat Streat enjoyed an additional treat with their lunch when a range of electric cars, including a top of the line Tesla, went on...",
			when: "1 DAY AGO",
			source: "NYT.COM"
		},
		{
			title: "Google self-driving car is tested on California highways",
			image: "http://i.imgur.com/MYB6HjU.jpg",
			body: "Visitors to Eat Streat enjoyed an additional treat with their lunch when a range of electric cars, including a top of the line Tesla, went on...",
			when: "1 DAY AGO",
			source: "NYT.COM"
		},
		{
			title: "Google self-driving car is tested on California highways",
			image: "http://i.imgur.com/MYB6HjU.jpg",
			body: "Visitors to Eat Streat enjoyed an additional treat with their lunch when a range of electric cars, including a top of the line Tesla, went on...",
			when: "1 DAY AGO",
			source: "NYT.COM"
		},
	];

	var create_message_view = Backbone.View.extend({
		initialize: function(){
			console.log('init message view');

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

	var result_view = Backbone.View.extend({
		events:{
			"click .tombstone": "active",
			"click .tombstone-active": "inactive"
		},
		template: _.template( $('#trend-result-template').html() ),
		initialize: function(){
			this.listenTo(this.model, "remove", this.removeFromDOM);
			this.listenTo(this.model, "change", this.update);
		},
		render: function(){
			this.$el.html( this.template(this.model.attributes) );
			//this.$el.hide();
			return this;
		},
		removeFromDOM: function(){
			var that = this;
			this.$el.fadeOut(200,function(){
				that.$el.remove();	
			});
		},
		update: function(){
			if( this.model.get('selected') ){
				this.$el.find('.tombstone').addClass('tombstone-active');
			}else{
				this.$el.find('.tombstone').removeClass('tombstone-active');
			}
		},
		active: function(){
			this.model.set('selected',true);

		},
		inactive: function(){
			this.model.set('selected',false);
		}
	});

	var trend_results_view = Backbone.View.extend({
		initialize: function(){
			this.collection.on("update",this.render,this);
		},
		render: function(){
			var view = this;
			view.$el.html('');
			this.collection.each(function(m){
				var result = new result_view({model: m});
				result.render();
				view.$el.append(result.$el);
			});
		}
	});

	var result_collection = Backbone.Collection.extend({
		model: trend_result
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
		}
	});

	
	//selected-trend-template

	$(function(){

		var results = new result_collection();

		var get_trending_topics = function(topic){
			topic = topic || '';
			$.getJSON(trend_api_host,{topic: topic},function(res){
				var trends_result = res.results;
				console.log(trends_result[0]);

				var format_share_res = function(shares){
					//check if less than 1k
					if(shares < 1000){
						return shares;
					}else{
						return Math.floor(shares/1000) + 'K'
					}
				};

				var format_time_ago = function(time){
					var cur = moment();
					var hours_ago = cur.diff( moment(time*1000) ,'hours');
					var days_ago = cur.diff( moment(time*1000) ,'days');
					var minutes_ago = cur.diff( moment(time*1000) ,'minutes');

					if(days_ago > 0){
						return days_ago + ' DAYS AGO';
					}else if(hours_ago > 0){
						return hours_ago + ' HOURS AGO';
					}else if(minutes_ago > 0){
						return minutes_ago + ' MINUTES AGO';
					}else{
						return 'JUST NOW';
					}
				};

				var new_trends = trends_result.map(function(t){
					
					var trend_obj = {
						"title": t.title,
						"image": t.thumbnail,
						"body": 'SDF DS FSDF SDFSDF SDF SDF SDFS DFSD FSF SDF ',
						"when": format_time_ago(t.published_date),
						"source": t.domain_name,
						"total_shares": format_share_res( t.total_shares ),
						"fb_shares": format_share_res(t.total_facebook_shares),
						"tw_shares": format_share_res(t.twitter_shares),
						"google_shares": format_share_res(t.google_plus_shares),
						"video":t.video
					};


					return trend_obj;
				});

				results.remove( results.models );
				results.add(new_trends);

			});
		};

		new create_message_view({el:"#create-alert", collection: results });
		var create_idea = new create_idea_modal({el: '#createIdea', collection: results });

		$('#trend-search').click(function(){
			var search_val = $('#trend-search-input').val() || "";
			get_trending_topics(search_val);
		});

		get_trending_topics();

		new trend_results_view({el: '#trend-results', collection: results});
	});

})(jQuery);

