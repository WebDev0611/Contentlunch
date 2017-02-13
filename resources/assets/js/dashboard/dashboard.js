(function($) {
    //from json via php
    var campaigns = new campaign_collection(my_campaigns);

    function createdInLast10Minutes(task) {
        const currentTime = moment.utc().format('x');
        const timeAgo = moment(task.created_at).format('x');

        return (currentTime - timeAgo) <= 60 * 10 * 1000;
    }

    function task_map(task) {
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

    function taskUpdateCallback(collection) {
        $('#incomplete-tasks').text(collection.length);
    }

    $('#incomplete-tasks').text(my_tasks.length);

    //runs the action to submit the task
    $('#add-task-button').click(function() {
        add_task(addTaskCallback);
    });

    function addTaskCallback(task) {
        let myTasksPromise = fetchMyTasks();
        let accountTasksPromise = fetchAccountTasks();

        $.when(myTasksPromise, accountTasksPromise).done((myTasksResponse, accountResponse) => {
            tasks.reset(myTasksResponse[0].data.map(task_map));
            all_tasks.reset(accountResponse[0].data.map(task_map));
            tab_container.show_my();
        });

        $('#addTaskModal').modal('hide');
    }

    function fetchMyTasks() {
        return $.ajax({
            url: '/api/tasks',
            method: 'get',
            headers: getJsonHeader(),
        })
    }

    function fetchAccountTasks() {
        return $.ajax({
            url: '/api/tasks',
            method: 'get',
            data: { 'account_tasks': '1' },
            headers: getJsonHeader(),
        })
    }

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