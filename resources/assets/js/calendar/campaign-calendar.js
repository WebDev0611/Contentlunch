
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
		//get the width to end cell
		var start_pos = $(this.model.attributes.start_sel).position();
		var end_pos = $(this.model.attributes.end_sel).position();
		this.$el.find('.calendar-timeline-task-item').width(end_pos.left - start_pos.left);
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


(function($){

	var dateCheck = function(c){
		if(c.start_date < c.end_date ){
			return true;
		}else{
			return false;
		}
	};

	var monthly_campaigns = {}; 
	campaigns.filter(dateCheck).forEach(function(c){
		var month_stub = moment(c.start_date).format('YYYY-M');
		if(monthly_campaigns[month_stub]){
			monthly_campaigns[month_stub].push(c);
		}else{
			monthly_campaigns[month_stub] = [c];
		}
	});

	var last_item = null;
	_.each(monthly_campaigns,function(mc,k){
		mc.map(function(c){
			c.start_sel = '#campaign-day-' + moment(c.start_date).format('YYYY-M-D');
			c.end_sel = '#campaign-day-' + moment(c.end_date).format('YYYY-M-D');
			return c;
		}).forEach(function(c,i){
			console.log(i);
			//each item in the month group, should move the verticle buffering
			var campaign = new campaign_model(c);
			var campaign_runner = new campaign_calendar_view({model: campaign});
			var offset = i * 40;
			campaign_runner.render();
			campaign_runner.$el.css('top',offset + 'px');
			$(c.start_sel).append( campaign_runner.$el );
			last_item = c;
		});
	});
	var first_pos = $(last_item.start_sel).position();
	$('.calendar-timeline-container').scrollLeft(first_pos.left)

})(jQuery);