/* trends views */

var trend_result_view = Backbone.View.extend({
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
			var result = new trend_result_view({model: m});
			result.render();
			view.$el.append(result.$el);
		});
	}
});