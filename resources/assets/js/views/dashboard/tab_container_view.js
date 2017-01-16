'use strict';

var dashboard_tab_container_view = Backbone.View.extend({
    events:{
        "click li.my-tasks": "show_my",
        "click li.all-tasks": "show_all",
        "click li.campaigns": "show_campaigns"
    },

    initialize: function(options) {
        this.allTasks = options.allTasks;
        this.myTasks = options.myTasks;
        this.show_my();
    },

    render: function() {
        $('.dashboard-tasks-container').each(function(index, element) {
            $(element).remove();
        });

        return this;
    },

    show_add_task_modal: function() {
        $('#addTaskModal').modal('show');
    },

    show_all: function() {
        this.remove_active();
        this.$el.find('.all-tasks').addClass('active');
        this.switchCollection(this.allTasks);
    },

    show_my: function() {
        this.remove_active();
        this.$el.find('.my-tasks').addClass('active');
        this.switchCollection(this.myTasks);
    },

    switchCollection: function(collection) {
        this.render();

        if (collection.length > 0) {
            this.append_open_tasks(collection);
        } else {
            this.append_empty_message();
        }

        $('#incomplete-tasks').text(this.collection.length);
    },

    append_open_tasks: function(collection) {
        this.open_tasks(collection).forEach(function(model) {
            var taskView = new task_view({ model: model });
            this.$el.find('.panel').append(taskView.render());
        }.bind(this));
    },

    open_tasks: function(collection) {
        return collection.filter(function(model) {
            return model.get('status') === 'open';
        });
    },

    append_empty_message: function() {
        this.$el.find('.panel').append(this.empty_tasks_text());
    },

    empty_tasks_text: function() {
        return $(
            '<div class="dashboard-tasks-container">' +
                '<div class="dashboard-tasks-cell">' +
                    '<h5 class="dashboard-tasks-title">No tasks: </h5> <a href="#">create one now</a>' +
                '</div>'+
            '</div>'
        ).click(this.show_add_task_modal.bind(this));
    },

    show_campaigns: function(){
        var view = this;
        this.remove_active();
        this.render();
        this.$el.find('.campaigns').addClass('active');

        this.campaigns.sortBy('timeago');
        this.append_campaigns();
    },

    append_campaigns: function() {
        this.campaigns.each(function(model) {
            var campaignView = new dashboard_campaign_view({ model: model });
            this.$el.find('.panel').append(campaignView.render());
        }.bind(this));
    },

    remove_active: function() {
        this.$el.find('.all-tasks').removeClass('active');
        this.$el.find('.my-tasks').removeClass('active');
        this.$el.find('.campaigns').removeClass('active');
    }
});