var task_model = Backbone.Model.extend({
	defaults:{
		name: 'Task Name Missing',
		start_date: '0000-00-00 00:00:00',
		due_date: '0000-00-00 00:00:00',
		explanation: 'Explanation Text Here',
		url: 'https://google.com',
		account_id: 0,
		campaign_id: 0,
		content_id: 0,
		user_id: 0,
		image: '/images/cl-avatar2.png',
        title: "",
        body: "",
        due: "",
        stage: "",
        timeago: 1470869716000,
        active: false
	}
});