var camelize = function(str) {
return str.replace(/(?:^\w|[A-Z]|\b\w|\s+)/g, function(match, index) {
if (+match === 0) return ""; // or if (/\s+/.test(match)) for white spaces
return index == 0 ? match.toLowerCase() : match.toUpperCase();
});
};


(function($){

	var topic_result = Backbone.Model.extend();

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
		template: _.template('<label for="<%= camelize(keyword) %>" class="checkbox-tag"><input id="<%= camelize(keyword) %>" type="checkbox"><span><%= keyword %></span></label>'),
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
		}
	});

	var long_tail_collection = Backbone.Collection.extend({
		model: topic_result
	});
	var short_tail_collection = Backbone.Collection.extend({
		model: topic_result
	});


	$(function(){
		var long_tail_results = new long_tail_collection();
		var short_tail_results = new short_tail_collection();

		$('#topic-search').click(function(){
			console.log('search clicked!');
			long_tail_results.remove( long_tail_results.models );
			short_tail_results.remove( short_tail_results.models );

			setTimeout(function(){
				short_tail_results.add(dummy_data_short);
				long_tail_results.add(dummy_data_long);
			},500);
		});

		long_tail_results.on('add',function(m){
			var result = new result_view({model: m});
			result.render();
			result.$el.fadeIn();
			$('#long-tail-results').append( result.el );
		});

		short_tail_results.on('add',function(m){
			var result = new result_view({model: m});
			result.render();
			result.$el.fadeIn();
			$('#short-tail-results').append( result.el );
		});

		long_tail_results.add(dummy_data_long);
		short_tail_results.add(dummy_data_short);

	});

})(jQuery);