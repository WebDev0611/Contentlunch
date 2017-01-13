(function($){
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

        function createdInLast10Minutes(task) {
            const currentTime = moment.utc().format('x');
            const timeAgo = moment(task.created_at).format('x');

            return (currentTime - timeAgo) <= 60 * 10 * 1000;
        }

        function task_map(task) {
            console.log(task.created_at);
            task.title = task.name;
            task.due = moment(task.due_date).format('MM/DD/YYYY');
            task.body = task.explanation;
            task.status = task.status;
            task.active = createdInLast10Minutes(task) ? 'active' : '';

            if (task.user.profile_image) {
                task.image = task.user.profile_image;
            }

            return task;
        }

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
        var ideas = new recent_ideas_view({
            el:'#recent-ideas',
            collection: recent_ideas
        });

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