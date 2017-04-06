'use strict';

var task_collection = Backbone.Collection.extend({
	model: task_model,

    populateList(contentId, openTasks = true) {
        return this.fetchData(contentId, openTasks).then(response => {
            this.remove(this.models);
            this.add(response.map(task => new task_model(task)));

            return response;
        });
    },

    fetchData(contentId, openTasks) {
        return $.ajax({
            method: 'get',
            url: `/api/contents/${contentId}/tasks`,
            headers: getJsonHeader(),
            data: {
                open: openTasks ? '1' : '0',
            },
        });
    },
});