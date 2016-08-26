/* models for the trends */

var trend_result = Backbone.Model.extend({
	defaults:{
		selected: false,
		author: 'N/A',
		title: 'Title Here',
		image: 'http://i.imgur.com/MYB6HjU.jpg',
		body: 'Default body text',
		when: "1 DAY AGO",
		source: "EXAMPLE.COM"
	}
});