'use strict';

var CampaignTasksCollection = Backbone.Collection.extend({
    model: task_model,

    populateList(campaignId, openTasks = true) {
        return this.fetchData(campaignId, openTasks).then(response => {
            this.remove(this.models);
            this.add(response.data.map(task => new task_model(task)));

            return response;
        });
    },

    fetchData(campaignId, openTasks) {
        return $.ajax({
            method: 'get',
            url: `/api/campaigns/${campaignId}/tasks`,
            headers: getJsonHeader(),
            data: {
                open: openTasks ? '1' : '0',
            },
        });
    },


});