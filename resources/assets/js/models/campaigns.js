/* campaign Backbone Models */
var campaign_model = Backbone.Model.extend({
	defaults:{
		title: 'Title',
		description: 'Description',
		goals: 'Goals go here',
		status: 0,
		campaign_type: 0
	}
});