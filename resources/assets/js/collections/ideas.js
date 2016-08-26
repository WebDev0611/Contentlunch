/* ideas collections */

var ideas_collection = Backbone.Collection.extend({
	url: '/ideas',
	model:idea_model
});

var recent_ideas_collection = Backbone.Collection.extend({
	url: '/ideas',
    model: recent_idea_model
});
