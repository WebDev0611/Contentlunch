/* campaign collections */
var campaign_collection = Backbone.Collection.extend({
	model: campaign_model,
    modelId: function (attrs) {
        return attrs.type + "-" + attrs.id;
    }
});