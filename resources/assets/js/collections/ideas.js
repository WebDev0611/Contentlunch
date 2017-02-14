/* ideas collections */

var ideas_collection = Backbone.Collection.extend({
	url: '/ideas',
	model: idea_model,

    fetch(options = {}) {
        options['X-CSRF-TOKEN'] = getToken();

        return Backbone.Collection.prototype.fetch.call(this, options);
    },
});

var recent_ideas_collection = Backbone.Collection.extend({
	url: '/ideas',
    model: recent_idea_model,

    fetch(options = {}) {
        options['X-CSRF-TOKEN'] = getToken();

        return Backbone.Collection.prototype.fetch.call(this, options);
    },
});
