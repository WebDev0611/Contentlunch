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

var recent_ideas_view = Backbone.View.extend({
    idea_views: [],
    initialize: function(){
        var that = this;
        this.listenTo( this.collection,'update', this.render );
        this.render();
    },
    render: function(){
        var that = this;    
        
        if(this.collection.length > 0){
        	this.collection.each(function(m){
            	that.idea_views.push( new recent_view({ model: m }) );
        	});
    	}else{
    		that.$el.append( $('<div class="dashboard-ideas-container"><div class="dashboard-ideas-cell">0 Ideas: <a href="/plan">Create One</a></div></div>') );
    	}
        this.idea_views.forEach(function(v){
            v.$el.hide();
            v.$el.fadeIn();
            that.$el.append( v.el );
        });
        return this;
    }
});

var recent_view = Backbone.View.extend({
    tagName: "div",
    className: "dashboard-ideas-container",
    events:{
        "mouseenter": "show_hover",
        "mouseleave": "hide_hover",

    },
    template: _.template( $('#idea-template').html() ),
    initialize: function(){
        this.$el.append( this.template(this.model.attributes) );
    },
    render: function(){
        return this;
    },
    show_hover: function(){
        this.$el.find('.idea-hover').toggleClass('hidden');
    },
    hide_hover: function(){
        this.$el.find('.idea-hover').toggleClass('hidden');
    },       
});