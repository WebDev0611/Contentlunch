(function($) {

    function taskUpdateCallback(collection) {
        $('#incomplete-tasks').text(collection.length);
    }

    // $('#incomplete-tasks').text(my_tasks.length);

    //  var activity_feed = new activity_collection(dummy_activity_data);
    //  var activity_feed_container = new activity_feed_view({el: '#activity-feed-container', collection: activity_feed });

    var recent_ideas = new recent_ideas_collection();
    recent_ideas.on('update', (collection) => $('.idea-count').text(collection.length));

    var ideas = new recent_ideas_view({
        el:'#recent-ideas',
        collection: recent_ideas
    });

    recent_ideas.fetch();

    var team_members = new team_members_collection();

    var team = new team_member_list_view({
        el: '#team-members-container',
        collection: team_members
    });

    team_members.fetch().then(team.render.bind(team));

})(jQuery);