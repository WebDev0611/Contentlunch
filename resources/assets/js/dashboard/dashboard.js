(function($){

    var task_view = Backbone.View.extend({
        template: _.template( $('#task-template').html() ),
        render: function(){
            this.el = this.template(this.model.attributes);
            return this.el;
        }
    });

    var campaign_view = Backbone.View.extend({
        template: _.template( $('#campaign-template').html() ),
        render: function(){
            this.el = this.template(this.model.attributes);
            return this.el;
        }
    });

    /* main tab view */
    var tab_container_view = Backbone.View.extend({
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
                var campaignView = new campaign_view({ model: model });
                this.$el.find('.panel').append(campaignView.render());
            }.bind(this));
        },

        remove_active: function() {
            this.$el.find('.all-tasks').removeClass('active');
            this.$el.find('.my-tasks').removeClass('active');
            this.$el.find('.campaigns').removeClass('active');
        }
    });

    var my_tasks_view = Backbone.View.extend();
    var all_tasks_view = Backbone.View.extend();

    /* activity item model */
    var activity_model = Backbone.Model.extend({
        defaults: {
            image: "/images/avatar.jpg",
            who: "Jane",
            action: "commented on",
            title: "Write blog post",
            content: "online banking",
            body: "uspendisse tincidunt eu lectus nec Suspen disse tincidunt eu lectus nec  vestibulum. Etiam eget dolor..."
        }
    });

    /* activity item collection */
    var activity_collection = Backbone.Collection.extend({
        model: activity_model
    });

    /* activity feed view */
    var activity_feed_view = Backbone.View.extend({
        initialize: function() {
            this.render();
        },

        render: function(){
            var view = this;
            this.collection.each(function(model) {
                var activity_item = new activity_item_view({ model: model });
                view.$el.append(activity_item.$el);
            });

            return this;
        }
    });

    /*activity item view */
    var activity_item_view = Backbone.View.extend({
        tagName: "div",
        className: "plan-activity-box-container",
        template: _.template($('#activity-item-template').html()),
        initialize: function() {
            this.$el.append(this.template(this.model.attributes));
        }
    });

    /*team member model */
    var team_member_model = Backbone.Model.extend({
        defaults:{
            "name": "Jason Simmons",
            "email": "jasonsimm@google.com",
            "image": "/images/avatar.jpg",
            "tasks": "35"
        }
    });

    /* team member collection */
    var team_members_collection = Backbone.Collection.extend({
        model: team_member_model
    });

    /* team member view */
    var team_member_view = Backbone.View.extend({
        tagName: "div",
        className: "dashboard-members-container",
        template: _.template( $('#team-member-template').html() ),
        initialize: function(){
            this.$el.append( this.template(this.model.attributes) );
        },
        render: function(){
            return this;
        }
    });

    /* team members list view */
    var team_members_view = Backbone.View.extend({
        events:{
            "click .team-member-modal-opener": "openTeamMemberInviteModal",
        },

        initialize: function() {
            this.render();
        },

        render: function() {
            var view = this;
            this.collection.each(function(m){
                var team_member = new team_member_view({model: m});
                view.$el.append( team_member.$el );
            });
        },

        openTeamMemberInviteModal: function() {
            var modal = new teamMemberInviteModalView({ el: '#modal-invite-team-member' });
        }
    });


    $(function(){
        //from json via php
        var campaigns = new campaign_collection(my_campaigns);

        var task_map = function(task) {
            task.title = task.name;
            task.due = task.due_date;
            task.body = task.explanation;
            task.timeago = moment(task.created_at).format('x');
            task.currenttime = moment.utc().format('x');
            task.status = task.status;

            if (task.user.profile_image) {
                task.image = task.user.profile_image;
            }

            return task;
        };

        var tasks = new task_collection(my_tasks.map(task_map));
        var all_tasks = new task_collection(account_tasks.map(task_map));

        var taskUpdateCallback = function(collection) {
            $('#incomplete-tasks').text(collection.length);
        };

        $('#incomplete-tasks').text(my_tasks.length);

        tasks.on('update', taskUpdateCallback);
        all_tasks.on('update', taskUpdateCallback);

        var tab_container = new tab_container_view({
            el: '#tab-container',
            collection: tasks,
            myTasks: tasks,
            allTasks: all_tasks,
        });

        tab_container.campaigns = campaigns;
        tab_container.tasks = tasks;

     //  var activity_feed = new activity_collection(dummy_activity_data);
     //  var activity_feed_container = new activity_feed_view({el: '#activity-feed-container', collection: activity_feed });

        var recent_ideas = new recent_ideas_collection();
        recent_ideas.on('update',function(c){
            $('.idea-count').text(c.length);
        });
        var ideas = new recent_ideas_view({el:'#recent-ideas', collection: recent_ideas});
        recent_ideas.fetch();

        var team_members = new team_members_collection(); //dummy_team_data
        var team = new team_members_view({
            el: '#team-members-container',
            collection: team_members
        });

        //runs the action to submit the task
        $('#add-task-button').click(function() {
            add_task(addTaskCallback);
        });

        function addTaskCallback(task) {
            tasks.add(new task_model(task_map(task)));
            $('#addTaskModal').modal('hide');
        }

    });

})(jQuery);