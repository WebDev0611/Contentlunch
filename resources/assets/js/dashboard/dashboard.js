(function($){
    /* main tab view */

var tab_container_view = Backbone.View.extend({
    initialize: function(){
        this.active_tab = 0;
        console.log('tc init');
        this.template = _.template( $('#tab-container-template').html() );
        this.tab_menu = new tab_menu_view();

    },
    render: function(){
        this.$el.append( this.template() );        
        //add the tab menu
        this.$el.find('#tab-menu-container').append( this.tab_menu.render() );
    },
});

var tab_menu_view = Backbone.View.extend({
    initialize: function(){
        console.log('tm init');
        this.template = _.template( $('#tab-menu-template').html() );
    },
    render: function(){
        return this.template();
    }
});

var my_tasks_view = Backbone.View.extend();
var all_tasks_view = Backbone.View.extend();
var campaigns_view = Backbone.View.extend();

/* activity feed view */
var activity_feed_view = Backbone.View.extend({
    initialize: function(){
        console.log('af init');
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
        console.log('ri init');
        this.template = _.template( $('#recent-ideas-template').html() );
    },
    render: function(){
        return this.template();
    }
});

/* team members view */
var team_members_view = Backbone.View.extend({
    initialize: function(){
        console.log('tm init');
        this.template = _.template( $('#team-members-template').html() );
    },
    render: function(){
        return this.template();
    }
});

var misc_container_view = Backbone.View.extend({
    initialize: function(){
        console.log('mc init');
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
    /*
    var tab_container = new tab_container_view({el: '#tab-container'});
    tab_container.render();
    var activity_feed_container = new activity_feed_view({el: '#activity-feed-container'});
    var misc_container = new misc_container_view({el: '#misc-container'});
    */
});

})(jQuery);