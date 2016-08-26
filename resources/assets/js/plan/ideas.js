/* the ideas tab/page */
(function(document, $){

	var idea_model = Backbone.Model.extend();
    var ideas_collection = Backbone.Collection.extend({url: '/ideas',model:idea_model});
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

    //kicks off the app
    $(function(){
    	var ideas = new ideas_collection();

    	ideas.fetch({
    		success: function(res){
    			var updated_time = res.map(function(m){
    				var a = m;
    				a.created_at = a.created_at * 1000;
    				a.updated_at = a.updated_at * 1000;
    				console.log(a);
    				return a;
    			});

    			ideas.reset(updated_time);
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