/* main tab view */

var tab_container_view = Backbone.View.extend({
    initialize: function(){
        console.log('tc init');
        this.template = _.template( $('#tab-container-template').html() );
        this.render();
    },
    render: function(){
        this.$el.append( this.template() );
    }
});


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
    var tab_container = new tab_container_view({el: '#tab-container'});
    var activity_feed_container = new activity_feed_view({el: '#activity-feed-container'});
    var misc_container = new misc_container_view({el: '#misc-container'});

});