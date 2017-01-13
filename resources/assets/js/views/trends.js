/* trends views */

var trend_result_view = Backbone.View.extend({
	events:{
		"click .tombstone": "active",
		"click .tombstone-active": "inactive"
	},
	template: _.template(`
		<div class="col-md-3">
		     <div class="tombstone">
				 <button type="button" data-target="#shareTrendModal" data-toggle="modal" class="button button-primary button-small text-uppercase tombstone-share">
					<i class="icon-share icon-vertically-middle"></i>&nbsp;SHARE
				</button>
		         <div class="tombstone-image">
		             <img src="<%= image %>" alt="">
		             <span><%= when %>  ·  <%= source %></span>
		         </div>
		         <div class="tombstone-container">
		             <h3><%= title %></h3>
		             <p>
		                 <%= author %>
		             </p>
		         </div>
		         <div class="tombstone-social">
		             <div class="tombstone-cell">
		                 <i class="icon-share"></i>
		                 <%= total_shares %>
		             </div>
		             <div class="tombstone-cell">
		                 <i class="icon-facebook-mini"></i>
		                 <%= fb_shares %>
		             </div>
		             <div class="tombstone-cell">
		                 <i class="icon-twitter2"></i>
		                 <%= tw_shares %>
		             </div>
		             <div class="tombstone-cell">
		                 <i class="icon-google-plus"></i>
		                 <%= google_shares %>
		             </div>
		             <div class="tombstone-cell">
		                 <i class="icon-youtube"></i>
		                 <%= video %>
		             </div>
		         </div>
		     </div>
		 </div>
	`),
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