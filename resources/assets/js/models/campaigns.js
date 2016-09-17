/* campaign Backbone Models */
var campaign_model = Backbone.Model.extend({
	defaults:{
		title: 'Title',
		description: 'Description',
		goals: 'Goals go here',
		status: 0,
		campaign_type: 0,
		image: '/images/avatar.jpg',
		due: '0 Days',
        body:"Suspendisse tincidunt eu lectus nec vestibulum. Etiam tincidunt eu lectus nec eget...",
        launched:"0 DAYS",
        stage: "0",
        timeago: 1470169716000,
        user_id: 0,
        performance: '100'
	}
});