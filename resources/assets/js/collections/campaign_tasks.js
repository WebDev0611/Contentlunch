'use strict';

var CampaignTasksCollection = Backbone.Collection.extend({
    model: task_model,

    populateList(campaignId) {
        return this.fetchData(campaignId).then(response => {
            this.remove(this.models);
            this.add(response.data.map(task => new task_model(task)));

            return response;
        });
    },

    fetchData(campaignId) {
        return $.ajax({
            method: 'get',
            url: `/api/campaigns/${campaignId}/tasks`,
            headers: getJsonHeader(),
            data: {
                open: '1'
            },
        });
    },


});