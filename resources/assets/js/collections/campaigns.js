/* campaign collections */
var campaignModelCounter = 1;
var campaign_collection = Backbone.Collection.extend({
	model: campaign_model,
	initialize: function (options) {
		this.on('add', function (model, collection, options) {
			model.set('id', campaignModelCounter);
			campaignModelCounter += 1;
		});
        this.on('reset', function (collection, options) {
            campaignModelCounter = 1;
        });
	}
});