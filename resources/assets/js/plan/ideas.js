(function(document, $){

	var idea_model = Backbone.Model.extend();
    var ideas_collection = Backbone.Collection.extend({url: '/ideas',model:idea_model});
    var idea_view = Backbone.View.extend({
    	template: _.template( $('#idea-template').html() ),
    	events:{},
    	initialize: function(){
    		this.render();
    	},
    	render: function(){
    		this.$el.html( this.template(this.model.attributes) );
    		return this;
    	}
    });

    var idea_container_view = Backbone.View.extend({
    	status: 'active',
    	events:{},
    	initialize: function(){
    		this.listenTo(this.collection,'update',this.updated);
    	},
    	updated: function(){
    		console.log('update in view triggered');
    		this.render(this.status);
    	},
    	render: function(status){
    		console.log('render called!');
    		this.status = status || 'active';
    		var view = this;

    		this.$el.html('');
    		var active = this.collection.where({status:this.status});
    		console.log(active);

    		_.each(active,function(m){
    			view.$el.append( new idea_view({model: m}).$el.html() );
    		});
    		return this;
    	}
    });

    //kicks off the app
    $(function(){
    	var ideas = new ideas_collection();

    	ideas.fetch({
    		success: function(res){
    			//console.log(res);
    		},
    		error: function(){
    			//console.log('ERROR RET');
    		}
    	});

    	//main collection - view
    	var ic = new idea_container_view({ 
    		el:'#idea-container',
    		collection: ideas 
    	});

    	$('#parked-ideas-link').click(function(){
    		ic.render('parked');
			$(this).parent().addClass('active');
			$('#active-ideas-link').parent().removeClass('active');
    	});
    	$('#active-ideas-link').click(function(){
    		ic.render('active');
    		$(this).parent().addClass('active');
			$('#parked-ideas-link').parent().removeClass('active');
    	});
    });


})(window.document, jQuery);