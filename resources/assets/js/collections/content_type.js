var content_type_collection = Backbone.Collection.extend({
    url: '/api/content-types',
    model: content_type_model
});