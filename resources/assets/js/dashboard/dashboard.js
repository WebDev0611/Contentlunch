(function($){
    var my_user_id = 1;

    /* tasks JS */
    var dummy_task_data = [
    {
        title: "Content mix: post 5 blogs, 2 social postings, 1 book per month",
        body:"Suspendisse tincidunt eu lectus nec vestibulum. Etiam tincidunt eu lectus nec eget...",
        due:"2 DAYS",
        stage: "3",
        image: "/images/avatar.jpg",
        timeago: 1470169716000,
        user_id: 1
    },
{
        title: "Twitter Update",
        body:"Suspendisse tincidunt eu lectus nec vestibulum. Etiam tincidunt eu lectus nec eget...",
        due:"7 DAYS",
        stage: "3",
        image: "/images/avatar.jpg",
        timeago: 1470269716000,
        user_id: 1
    },
{
        title: "Write LinkedIn article",
        body:"Suspendisse tincidunt eu lectus nec vestibulum. Etiam tincidunt eu lectus nec eget...",
        due:"2 DAYS",
        stage: "3",
        image: "/images/avatar.jpg",
        timeago: 1470369716000,
        user_id: 1
    },
{
        title: "Content mix: post 7 blogs, 16 social postings, 1 book per month",
        body:"Suspendisse tincidunt eu lectus nec vestibulum. Etiam tincidunt eu lectus nec eget...",
        due:"2 DAYS",
        stage: "3",
        image: "/images/avatar.jpg",
        timeago: 1470469716000,
        user_id: 2
    },
{
        title: "Content mix: post 3 blogs, 16 social postings, 1 book per month",
        body:"Suspendisse tincidunt eu lectus nec vestibulum. Etiam tincidunt eu lectus nec eget...",
        due:"2 DAYS",
        stage: "3",
        image: "/images/avatar.jpg",
        timeago: 1470569716000,
        user_id: 2
    },
{
        title: "Content mix: post 1 blogs, 16 social postings, 1 book per month",
        body:"Suspendisse tincidunt eu lectus nec vestibulum. Etiam tincidunt eu lectus nec eget...",
        due:"5 DAYS",
        stage: "3",
        image: "/images/avatar.jpg",
        timeago: 1470669716000,
        user_id: 2
    },
{
        title: "Content mix: post 1 blogs, 16 social postings, 1 book per month",
        body:"Suspendisse tincidunt eu lectus nec vestibulum. Etiam tincidunt eu lectus nec eget...",
        due:"7 DAYS",
        stage: "3",
        image: "/images/avatar.jpg",
        timeago: 1470769716000,
        user_id: 2
    },
{
        title: "Content mix: post 1 blogs, 16 social postings, 1 book per month",
        body:"Suspendisse tincidunt eu lectus nec vestibulum. Etiam tincidunt eu lectus nec eget...",
        due:"2 DAYS",
        stage: "3",
        image: "/images/avatar.jpg",
        timeago: 1470869716000,
        user_id: 2
    },
    ];

    var task_model = Backbone.Model.extend({
        defaults:{
            title: "",
            body: "",
            due: "",
            stage: "",
            image: "",
            timeago: 1470869716000,
            active: false
        }
    });
    var tasks_collection = Backbone.Collection.extend({
        model: task_model
    });

    var task_view = Backbone.View.extend({
        template: _.template( $('#task-template').html() ),
        render: function(){
            this.el = this.template(this.model.attributes);
            return this.el;
        }
    });


    /* main tab view */
    var tab_container_view = Backbone.View.extend({
        events:{
            "click li.my-tasks": "show_my",
            "click li.all-tasks": "show_all"
        },
        initialize: function(){
            this.active_user = my_user_id;
            console.log('tc init');
            
            this.collection = new tasks_collection();

        },
        render: function(){
            var that = this;
            $('.dashboard-tasks-container').each(function(i,e){
                $(e).remove();
            });

            this.collection.sortBy('timeago');
            this.collection.each(function(m){
                    var t = new task_view({ model: m });
                    that.$el.find('.panel').append( t.render() );
            });
            return this;
        },
        show_my: function(){
            this.$el.find('.all-tasks').removeClass('active');
            this.$el.find('.my-tasks').addClass('active');
            this.collection.reset( dummy_task_data.filter(function(t){
                return (t.user_id === my_user_id );
            }) );
            this.render();
        },
        show_all: function(){
            this.$el.find('.my-tasks').removeClass('active');
            this.$el.find('.all-tasks').addClass('active');
            this.collection.reset( dummy_task_data );
            this.render();
        }
    });

    var my_tasks_view = Backbone.View.extend();
    var all_tasks_view = Backbone.View.extend();
    var campaigns_view = Backbone.View.extend();

    /* activity feed view */
    var activity_feed_view = Backbone.View.extend({
        initialize: function(){
            this.template = _.template( $('#activity-feed-template').html() );
            this.render();
        },
        render: function(){
            this.$el.append( this.template() );
        }
    });

    /* recent ideas view */
    var recent_ideas_view = Backbone.View.extend({
        initialize: function(){
            this.template = _.template( $('#recent-ideas-template').html() );
        },
        render: function(){
            return this.template();
        }
    });

    /* team members view */
    var team_members_view = Backbone.View.extend({
        initialize: function(){
            this.template = _.template( $('#team-members-template').html() );
        },
        render: function(){
            return this.template();
        }
    });

    var misc_container_view = Backbone.View.extend({
        initialize: function(){
            this.render();
        },
        render: function(){
            var recent_ideas = new recent_ideas_view();
            var team_members = new team_members_view();
            this.$el.append( recent_ideas.render() );
            this.$el.append( team_members.render() );
        }
    });

    /* campaigns view */
    var campaigns_view = Backbone.View.extend({
        initialize: function(){},
        render: function(){}
    });


    $(function(){
        var tab_container = new tab_container_view({el: '#tab-container'});
        var activity_feed_container = new activity_feed_view({el: '#activity-feed-container'});
        var misc_container = new misc_container_view({el: '#misc-container'});
        
    });

})(jQuery);