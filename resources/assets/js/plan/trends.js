var camelize = function(str) {
return str.replace(/(?:^\w|[A-Z]|\b\w|\s+)/g, function(match, index) {
if (+match === 0) return ""; // or if (/\s+/.test(match)) for white spaces
return index == 0 ? match.toLowerCase() : match.toUpperCase();
});
};


(function($){

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
			this.listenTo(this.collection,"change",this.render);
			this.listenTo(this.collection,"update",this.render);

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
			"click .tombstone": "active"
		},
		template: _.template( $('#trend-result-template').html() ),
		initialize: function(){
			this.listenTo(this.model, "remove", this.removeFromDOM);
		},
		render: function(){
			this.$el.html( this.template(this.model.attributes) );
			this.$el.hide();
			return this;
		},
		removeFromDOM: function(){
			console.log('REMOVED!!');
			var that = this;
			this.$el.fadeOut(200,function(){
				that.$el.remove();	
			});
		},
		active: function(){
			this.$el.find('.tombstone').toggleClass('tombstone-active');
			if( this.model.get('selected') ){
				this.model.set('selected',false);
			}else{
				this.model.set('selected',true);
			}
		}
	});

	var result_collection = Backbone.Collection.extend({
		model: trend_result
	});


	$(function(){

		var results = new result_collection();

		new create_message_view({el:"#create-alert", collection: results });

		results.on('add',function(m){
			var result = new result_view({model: m});
			result.render();
			$('#trend-results').append( result.el );
			result.$el.fadeIn(250);
		});

		$('#trend-search').click(function(){
			console.log('search clicked!');
			results.remove( results.models );
			setTimeout(function(){
				results.add(dummy_data);
			},500);
		});

		results.add(dummy_data);
	});

})(jQuery);