var camelize = function(str) {
return str.replace(/(?:^\w|[A-Z]|\b\w|\s+)/g, function(match, index) {
if (+match === 0) return ""; // or if (/\s+/.test(match)) for white spaces
return index == 0 ? match.toLowerCase() : match.toUpperCase();
});
};


(function($){

	var influencer_result = Backbone.Model.extend({
		defaults:{
			selected: false
		}
	});

	//place holder data
	var dummy_data = [
		{
			title: "Jane Doe",
			image: "/images/avatar-new.jpg",
			body: "Suspendisse tincidunt eu lectus nec vestibulum. Etiam eget dolor...",
			twitter_followers: "3,300",
			facebook_followers: "2,503"
		},
		{
			title: "Mary Ipsum",
			image: "/images/avatar-new.jpg",
			body: "Suspendisse tincidunt eu lectus nec vestibulum. Etiam eget dolor...",
			twitter_followers: "3,300",
			facebook_followers: "2,503"
		},
		{
			title: "Carol Lorem",
			image: "/images/avatar-new.jpg",
			body: "Suspendisse tincidunt eu lectus nec vestibulum. Etiam eget dolor...",
			twitter_followers: "3,300",
			facebook_followers: "2,503"
		},
		{
			title: "Maria Content",
			image: "/images/avatar-new.jpg",
			body: "Suspendisse tincidunt eu lectus nec vestibulum. Etiam eget dolor...",
			twitter_followers: "3,300",
			facebook_followers: "2,503"
		},
	];

	var invite_message_view = Backbone.View.extend({
		initialize: function(){
			this.listenTo(this.collection,"change",this.render);
			this.listenTo(this.collection,"update",this.render);

		},
		render: function(){
			if( this.collection.length > 0){
				this.$el.html(this.collection.length + ' persons found - select person you want to invite to work on project');
			}else{
				this.$el.html('');
			}
		}
	});

	var influencer_data_modal = Backbone.View.extend({
		events:{
			"click .sidemodal-close": "dismiss"
		},
		initialize: function(){
			console.log('new modal init!');
			this.render();
		},
		render: function(){
			this.$el.find('.title').text( this.model.get('title') );
			this.$el.find('.desc').text( this.model.get('desc') );
			this.$el.find('.user-avatar').html('<img src="' + this.model.get('image') + '" alt="' + this.model.get('title') + '">');
			//this.$el.find('.influencer-desc').text(this.model.get('desc') );

			$('#modal-influencerdetails').modal('show');
			return this;
		},
		dismiss: function(){
			$('#modal-influencerdetails').modal('hide');
			//this.remove();
		}
	});

	var influencer_invite_modal = Backbone.View.extend({
		events:{
			"click .sidemodal-close": "dismiss"
		},
		initialize: function(){
			console.log('new modal init!');
			this.render();
		},
		render: function(){
			this.$el.find('.title').text( this.model.get('title') );
			$('#modal-inviteinfluencer').modal('show');
			return this;
		},
		dismiss: function(){
			$('#modal-inviteinfluencer').modal('hide');
			//this.remove();
		}
	});

	var result_view = Backbone.View.extend({
		events:{
			"click .details": "showDetails",
			"click .invite": "invite",
		},
		template: _.template( $('#influencer-template').html() ),
		tagName: 'li',
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
		showDetails: function(){
			 //data-toggle="modal" data-target="#modal-influencerdetails"
			new influencer_data_modal({el:"#modal-influencerdetails", model: this.model});
		},
		invite: function(){
			new influencer_invite_modal({el:"#modal-inviteinfluencer", model: this.model});	
		}

	});

	var result_collection = Backbone.Collection.extend({
		model: influencer_result
	});


	$(function(){
		var influ_api_host = '/influencers';

		var results = new result_collection();

		var search_influencers = function(topic){
			$.getJSON(influ_api_host,{topic:topic},function(res){
				console.log(res.results);
				results.remove( results.models );
				results.add( res.results.map(influencer_map) );
			});
		};
		var influencer_map = function(i){
			return {
				title: i.name,
				image: i.image,
				body: i.display_bio,
				desc: i.bio,
				twitter_num: i.num_followers,
				facebook_num: 0,
				twitter_link: (i.twitter_id_str) ? ('https://twitter.com/intent/user?user_id=' + i.twitter_id_str) : '',
			};
		};


		new invite_message_view({el:"#influencer-alert", collection: results });

		results.on('add',function(m){
			var result = new result_view({model: m});
			result.render();
			$('#influencer-results').append( result.el );
			result.$el.fadeIn(250);
		});

		$('#influencer-search').click(function(){
			console.log('search clicked!');
			var top = $("#influencer-topic-val").val();

			search_influencers(top);

			// results.remove( results.models );
			// setTimeout(function(){
			// 	results.add(dummy_data);
			// },500);
		});

		//results.add(dummy_data);
	});

})(jQuery);