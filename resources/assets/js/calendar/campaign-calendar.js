
var campaign_calendar_view = Backbone.View.extend({
	className: 'calendar-timeline-task',
	tagName: 'div',
	template: _.template( $('#campaign-template').html() ),
	events:{
		'click': 'preview',
		'click alendar-task-list-popover-close': 'dismiss'
	},
	render: function(){
		this.$el.append( this.template( this.model.attributes ));
		return this;
	},
	preview: function(){
		this.$el.find('.calendar-timeline-task-item').toggleClass('active');
		this.$el.find('.calendar-task-list-popover').toggleClass('open');
	},
	dismiss: function(){
		this.$el.find('.calendar-task-list-popover').toggleClass('open');
	}
});
var campaign_model = Backbone.Model.extend();


(function($){

	var my_campaigns = campaigns.filter(function(c){
		if(c.start_date < c.end_date ){
			return true;
		}else{
			return false;
		}
	})
	.map(function(c){

		c.start_sel = '#campaign-day-' + moment(c.start_date).format('YYYY-M-D');
		c.end_sel = '#campaign-day-' + moment(c.end_date).format('YYYY-M-D');
		return c;
	});



	my_campaigns.forEach(function(c){
		var campaign = new campaign_model(c);
		var campaign_runner = new campaign_calendar_view({model: campaign});
		console.log(c.start_sel);
		$(c.start_sel).append( campaign_runner.render().$el );
		console.log(campaign_runner);
	});


	console.log(my_campaigns);
})(jQuery);