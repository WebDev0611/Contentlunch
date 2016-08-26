(function($){
    var my_user_id = 1;

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

    /* dummy content data */
    var dummy_content_data = [
    {
        image:"/images/avatar.jpg",
        title: "Content mix: post 3 blogs, 16 social postings, 1 book per month",
        launched:"05/05/2016",
        performance:"11K"  
    },
    {
        image:"/images/avatar.jpg",
        title: "Post 1 blog, 1 book per month",
        launched:"05/05/2016",
        performance:"11K"  
    },
    {
        image:"/images/avatar.jpg",
        title: " 16 social postings",
        launched:"05/05/2016",
        performance:"11K"  
    },
    {
        image:"/images/avatar.jpg",
        title: "Content mix: post 3 blogs, 16 social postings, 1 book per month",
        launched:"05/05/2016",
        performance:"11K"  
    },
    {
        image:"/images/avatar.jpg",
        title: "Content mix: post 2 blogs, 20 social postings",
        launched:"05/05/2016",
        performance:"11K"  
    },
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
    var dummy_campaign_data = [
    {
        title: "CAMPAIGN 1",
        body:"Suspendisse tincidunt eu lectus nec vestibulum. Etiam tincidunt eu lectus nec eget...",
        launched:"2 DAYS",
        stage: "3",
        image: "/images/avatar.jpg",
        timeago: 1470169716000,
        user_id: 1,
        performance: '40'
    },
        {
        title: "CAMPAIGN 2",
        body:"Suspendisse tincidunt eu lectus nec vestibulum. Etiam tincidunt eu lectus nec eget...",
        launched:"7 DAYS",
        stage: "3",
        image: "/images/avatar.jpg",
        timeago: 1470269716000,
        user_id: 1,
        performance: '55',
    }
    ];


    /* content item model */
    var content_model = Backbone.Model.extend({
        defaults:{
            image:"/images/avatar.jpg",
            title: "Content mix: post 3 blogs, 16 social postings, 1 book per month",
            launched:"05/05/2016",
            performance:"11K"
        }
    });
    /* content item collection */
    var content_collection = Backbone.Collection.extend({
        model: content_model
    });

    /* content item view */
    var content_item_view = Backbone.View.extend({
        tagName: "li",
        template: _.template( $('#content-item-template').html() ),
        initialize: function(){
            this.$el.append( this.template(this.model.attributes) );
            return this;
        },
        render: function(){
            return this;
        }
    });

    // /* main tab view */
    var tab_container_view = Backbone.View.extend({
        content:{},
        campaigns:{},
        active: 'content',
        events:{
            "click .top-content": "show_content",
            "click .active-campaigns": "show_campaigns"
        },
        initialize: function(){
          //  this.content || this.campaigns || [];
        },
        render:function(){
            var that = this;
            this.$el.find('#tab-contents-cont').html('');
            this.collection.each(function(m){
                var con = null;
                switch(this.active){
                    case 'campaigns':
                        con = new campaign_item_view({model: m });
                    break;
                    default:
                        con = new content_item_view({model: m });
                    break;

                }
                that.$el.find('#tab-contents-cont').append( con.$el );
            });
           
        },
        show_content: function(){
            this.collection = this.content || [];
            this.active = 'content';
            this.remove_active();
            this.$el.find('.top-content').addClass('active');
            this.render();
        },
        show_campaigns: function(){
            this.collection = this.campaigns || [];
            this.active = 'campaigns';
            this.remove_active();
            this.$el.find('.active-campaigns').addClass('active');
            this.render();
        },
        remove_active: function(){
            this.$el.find('.top-content').removeClass('active');
            this.$el.find('.active-campaigns').removeClass('active');          
        }
    });

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
            console.log( this.collection.toJSON() );
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


    /* recent ideas view */
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

    /* campaign parts */
    var campaign_model = Backbone.Model.extend({
        defaults:{
            title: "CAMPAIGN NAME",
            body:"Suspendisse tincidunt eu lectus nec vestibulum. Etiam tincidunt eu lectus nec eget...",
            launched:"0 DAYS",
            stage: "0",
            image: "/images/avatar.jpg",
            timeago: 1470169716000,
            user_id: 1,
            performance: '100'
        }
    });
    var campaign_collection = Backbone.Collection.extend({
        model: campaign_model
    });

    /* campaigns view */
    var campaign_item_view = Backbone.View.extend({
        tagName: "li",
        template: _.template( $('#campaign-item-template').html() ),
        initialize: function(){
            this.$el.append( this.template(this.model.attributes) );
            return this;
        },
        render: function(){
            return this;
        }
    });

     $(function(){
           var tab_container = new tab_container_view({el: '#tab-container'});
           tab_container.content = new content_collection(dummy_content_data);
           tab_container.campaigns = new campaign_collection(dummy_campaign_data);
           tab_container.show_content();

           var activity_feed = new activity_collection(dummy_activity_data);
           var activity_feed_container = new activity_feed_view({el: '#activity-feed-container', collection: activity_feed });

// //        var misc_container = new misc_container_view({el: '#misc-container'});

           var recent_ideas = new recent_ideas_collection(dummy_ideas_data);
           var ideas = new recent_ideas_view({el:'#recent-ideas',collection: recent_ideas});
     });

})(jQuery);