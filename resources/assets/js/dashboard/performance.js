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
        tagName: "div",
        className: "dashboard-performing-container",
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
        events:{
            "click .top-content": "show_content",
            "click .active-campaigns": "show_campaigns"
        },
        render:function(){
            var that = this;
            this.content.each(function(m){
                var con = new content_item_view({model: m });
                that.$el.append( con.$el );
            });
           
        },
        show_content: function(){
            this.$el.find('.top-content').toggleClass('active');
            this.$el.find('.active-campaigns').toggleClass('active');
        },
        show_campaigns: function(){
            this.$el.find('.top-content').toggleClass('active');
            this.$el.find('.active-campaigns').toggleClass('active');
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

    /* campaigns view */
    var campaigns_view = Backbone.View.extend({
        initialize: function(){},
        render: function(){}
    });

     $(function(){
           var tab_container = new tab_container_view({el: '#tab-container'});
           tab_container.content = new content_collection(dummy_content_data);
           tab_container.render();

           var activity_feed = new activity_collection(dummy_activity_data);
           var activity_feed_container = new activity_feed_view({el: '#activity-feed-container', collection: activity_feed });

// //        var misc_container = new misc_container_view({el: '#misc-container'});

           var recent_ideas = new recent_ideas_collection(dummy_ideas_data);
           var ideas = new recent_ideas_view({el:'#recent-ideas',collection: recent_ideas});
     });

})(jQuery);