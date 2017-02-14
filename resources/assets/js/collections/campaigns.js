'use strict';

var campaign_collection = Backbone.Collection.extend({
	model: campaign_model,

    populateList() {
        return this.fetchData(campaignId).then(response => {
            this.remove(this.models);
            this.add(response.data.map(task => new task_model(task)));

            return response;
        });
    },

    fetchData() {
        return $.ajax({
            method: 'get',
            url: `/api/campaigns`,
            headers: getJsonHeader(),
        });
    },

    modelId: function (attrs) {
        return attrs.type + "-" + attrs.id;
    }
});