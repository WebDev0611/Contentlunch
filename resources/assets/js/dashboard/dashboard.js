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

    var dummy_campaign_data = [
    {
        title: "CAMPAIGN 1",
        body:"Suspendisse tincidunt eu lectus nec vestibulum. Etiam tincidunt eu lectus nec eget...",
        due:"2 DAYS",
        stage: "3",
        image: "/images/avatar.jpg",
        timeago: 1470169716000,
        user_id: 1
    },
        {
        title: "CAMPAIGN 2",
        body:"Suspendisse tincidunt eu lectus nec vestibulum. Etiam tincidunt eu lectus nec eget...",
        due:"7 DAYS",
        stage: "3",
        image: "/images/avatar.jpg",
        timeago: 1470269716000,
        user_id: 1
    }
    ];

   var dummy_activity_data = [
    {
        image: "/images/avatar.jpg",
        who: "Jane",
        action: "commented on",
        title: "Write blog post",
        content: "online banking",
        body: "uspendisse tincidunt eu lectus nec Suspen disse tincidunt eu lectus nec  vestibulum. Etiam eget dolor..."
    },
    {
        image: "/images/avatar.jpg",
        who: "Jane",
        action: "commented on",
        title: "Write blog post",
        content: "online banking",
        body: "uspendisse tincidunt eu lectus nec Suspen disse tincidunt eu lectus nec  vestibulum. Etiam eget dolor..."
    },
    {
        image: "/images/avatar.jpg",
        who: "Jane",
        action: "commented on",
        title: "Write blog post",
        content: "online banking",
        body: "uspendisse tincidunt eu lectus nec Suspen disse tincidunt eu lectus nec  vestibulum. Etiam eget dolor..."
    },
    {
        image: "/images/avatar.jpg",
        who: "Jane",
        action: "commented on",
        title: "Write blog post",
        content: "online banking",
        body: "uspendisse tincidunt eu lectus nec Suspen disse tincidunt eu lectus nec  vestibulum. Etiam eget dolor..."
    },
    {
        image: "/images/avatar.jpg",
        who: "Jane",
        action: "commented on",
        title: "Write blog post",
        content: "online banking",
        body: "uspendisse tincidunt eu lectus nec Suspen disse tincidunt eu lectus nec  vestibulum. Etiam eget dolor..."
    },
    ];

    /* recent ideas view */
    var dummy_ideas_data = [
        {
            image:'/images/avatar.jpg',
            title: 'Content mix: post 16 soc',
            timeago:'3 Days Ago'
        },
        {
            image:'/images/avatar.jpg',
            title: 'Content mix: post 16 soc',
            timeago:'3 Days Ago'
        },
        {
            image:'/images/avatar.jpg',
            title: 'Content mix: post 16 soc',
            timeago:'3 Days Ago'
        },
        {
            image:'/images/avatar.jpg',
            title: 'Content mix: post 16 soc',
            timeago:'3 Days Ago'
        },
        {
            image:'/images/avatar.jpg',
            title: 'Content mix: post 16 soc',
            timeago:'3 Days Ago'
        },
        {
            image:'/images/avatar.jpg',
            title: 'Content mix: post 16 soc',
            timeago:'3 Days Ago'
        },
    ];

    var dummy_team_data = [
    // {
    //    // name: "Jason Simmons",
    //    // email: "jasonsimm@google.com",
    //    // image: "/images/avatar.jpg",
    //   //  num: "35"
    // },
    // {
    //     name: "Jason Simmons",
    //     email: "jasonsimm@google.com",
    //     image: "/images/avatar.jpg",
    //     num: "35"
    // },
    {
        name: "Jane Samson",
        email: "jsam@google.com",
        image: "/images/avatar.jpg",
        num: "35"
    },
    {
        "name": "Jason Simmons",
        "email": "jasonsimm@google.com",
        "image": "/images/avatar.jpg",
        "tasks": "35"
    }
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

    /* campaign parts */
    var campaign_model = Backbone.Model.extend();
    var campaign_collection = Backbone.Collection.extend({
        model: campaign_model
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
        initialize: function(){
            this.active_user = my_user_id;
            console.log('tc init');
            
            this.collection.reset( this.collection.filter(function(t){
                return (t.user_id === my_user_id );
            }).sort(function(a,b){
                return b.timeago - a.timeago;
            }) );

            this.show_my();
        },
        render: function(){
            var that = this;
            $('.dashboard-tasks-container').each(function(i,e){
                $(e).remove();
            });
            return this;
        },
        show_my: function(){
            var view = this;
            this.remove_active();
            this.render();
            this.$el.find('.my-tasks').addClass('active');
            this.collection.reset( dummy_task_data.filter(function(t){
                return (t.user_id === my_user_id );
            }).sort(function(a,b){
                return b.timeago - a.timeago;
            }) );
            this.collection.sortBy('timeago');
            this.collection.each(function(m){
                    var t = new task_view({ model: m });
                    view.$el.find('.panel').append( t.render() );
            });
            $('#incomplete-tasks').text( this.collection.length );
        },
        show_all: function(){
            var view = this;
            this.remove_active();
            this.render();
            this.$el.find('.all-tasks').addClass('active');
            this.collection.reset( dummy_task_data.sort(function(a,b){
                return b.timeago - a.timeago;
            }) );
            this.collection.sortBy('timeago');
            this.collection.each(function(m){
                    var t = new task_view({ model: m });
                    view.$el.find('.panel').append( t.render() );
            });
            $('#incomplete-tasks').text( this.collection.length );
        },
        show_campaigns: function(){
            var view = this;
            this.remove_active();
            this.render();          
            this.$el.find('.campaigns').addClass('active');
            console.log(this.campaigns.toJSON() );

            this.campaigns.sortBy('timeago');
            this.campaigns.each(function(m){

                    var t = new campaign_view({ model: m });
                    view.$el.find('.panel').append( t.render() );
            });
        },
        remove_active: function(){
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
        initialize: function(){
            this.render();
        },
        render: function(){
            var view = this;
            this.collection.each(function(m){
                var activity_item = new activity_item_view({model: m});
                view.$el.append( activity_item.$el );
            });
            return this;
        }
    });

    /*activity item view */
    var activity_item_view = Backbone.View.extend({
        tagName: "div",
        className: "plan-activity-box-container",
        template: _.template( $('#activity-item-template').html() ),
        initialize: function(){
            this.$el.append( this.template( this.model.attributes ) );
        }
    });

    var recent_ideas_view = Backbone.View.extend({
        idea_views: [],
        initialize: function(){
            var that = this;
            this.collection.each(function(m){
                that.idea_views.push( new recent_view({ model: m }) );
            });
            this.render();
        },
        render: function(){
            var that = this;    
            this.idea_views.forEach(function(v){
                v.$el.hide();
                v.$el.fadeIn();
                that.$el.append( v.el );
            });
            return this;
        }
    });
    var recent_view = Backbone.View.extend({
        tagName: "div",
        className: "dashboard-ideas-container",
        events:{
            "mouseenter": "show_hover",
            "mouseleave": "hide_hover",

        },
        template: _.template( $('#recent-template').html() ),
        initialize: function(){
            this.$el.append( this.template(this.model.attributes) );
        },
        render: function(){
            return this;
        },
        show_hover: function(){
            this.$el.find('.idea-hover').toggleClass('hidden');
        },
        hide_hover: function(){
            this.$el.find('.idea-hover').toggleClass('hidden');
        },       
    });
    var recent_idea_model = Backbone.Model.extend();
    var recent_ideas_collection = Backbone.Collection.extend({
        model: recent_idea_model
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
        initialize: function(){
            console.log(this);
            this.render();
        },
        render: function(){
            var view = this;
            this.collection.each(function(m){
                var team_member = new team_member_view({model: m});
                view.$el.append( team_member.$el );
            });
        }
    });


    $(function(){
        var campaigns = new campaign_collection(dummy_campaign_data);
        var tasks = new tasks_collection(dummy_task_data.filter(function(t){
                return (t.user_id === my_user_id );
            }).sort(function(a,b){
                return b.timeago - a.timeago;
            }) );

        var tab_container = new tab_container_view({el: '#tab-container',collection: tasks});
        tab_container.campaigns = campaigns;
        tab_container.tasks = tasks;

       var activity_feed = new activity_collection(dummy_activity_data);
       var activity_feed_container = new activity_feed_view({el: '#activity-feed-container', collection: activity_feed });

        var recent_ideas = new recent_ideas_collection(dummy_ideas_data);
        var ideas = new recent_ideas_view({el:'#recent-ideas', collection: recent_ideas});

        var team_members = new team_members_collection(dummy_team_data);
        var team = new team_members_view({el: '#team-members-container', collection: team_members});
    });

})(jQuery);