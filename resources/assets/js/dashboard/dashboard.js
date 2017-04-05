$(document).ready(function() {

    setUpTask();

    function taskUpdateCallback(collection) {
        $('#incomplete-tasks').text(collection.length);
    }

    // $('#incomplete-tasks').text(my_tasks.length);

    //  var activity_feed = new activity_collection(dummy_activity_data);
    //  var activity_feed_container = new activity_feed_view({el: '#activity-feed-container', collection: activity_feed });

    var recent_ideas = new recent_ideas_collection();
    recent_ideas.on('update', function (collection) {
        return $('.idea-count').text(this.last30Days().length);
    });

    var ideas = new recent_ideas_view({
        el: '#recent-ideas',
        collection: recent_ideas
    });

    recent_ideas.fetch();

    var recent_content = new content_collection();
    recent_content.on('update', function (collection) {
        return $('.content-count').text(this.last30Days().length);
    });
    recent_content.fetch();

    var team_members = new team_members_collection();

    var team = new team_member_list_view({
        el: '#team-members-container',
        collection: team_members
    });

    team_members.fetch().then(team.render.bind(team));

    function setUpTask() {
        $('#add-task-button').click(function () {
            add_task(addTaskCallback);
        });

        function addTaskCallback(task) {
            $('#addTaskModal').modal('hide');
            window.location.reload();
        }
    }
});