/* ideas views */

var idea_view = Backbone.View.extend({
	className: "plan-ideas-container",
	events:{
		"click li#write-it-btn": "write"
	},
	template: _.template( $('#idea-template').html() ),
	initialize: function(){
		this.render();
	},
	render: function(){
		this.$el.html( this.template(this.model.attributes) );
		if(this.model.get('status') == 'parked'){
			this.$el.find('#park-it-btn').hide();
		}else{
			this.$el.find('#park-it-btn').show();
		}
		return this;
	},
	write: function(){
		console.log("write it clicked");
		window.location.href = '/idea/' + this.model.get('id');
	}

});

var idea_container_view = Backbone.View.extend({
	status: 'active',
	events:{},
	initialize: function(){
		this.listenTo(this.collection,'update',this.updated);
	},
	updated: function(){
		this.render(this.status);
	},
	render: function(status){
		this.status = status || 'active';
		var view = this;

		this.$el.html('');
		var active = this.collection.where({status:this.status});

		_.each(active,function(m){
			view.$el.append( new idea_view({model: m}).$el );
		});
		return this;
	}
});