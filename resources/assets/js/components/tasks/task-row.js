'use strict';

Vue.component('task-row', {
    template: `
        <div class="dashboard-tasks-container">
            <div class="dashboard-tasks-cell cell-size-5">
                <div class="dashboard-tasks-img-wrapper">
                    <img :src="task.profile_image" alt="#" class="dashboard-tasks-img">
                </div>
            </div>
            <div class="dashboard-tasks-cell cell-size-80">
                <h5 class="dashboard-tasks-title">
                    <a :href="link">
                        {{ task.name }}
                    </a>
                </h5>
                <span class="dashboard-tasks-text">
                    {{ task.explanation }}
                </span>
                <ul class="dashboard-tasks-list">
                    <li v-if='task.due_date'>
                        DUE ON: <strong>{{ task.due_date }}</strong>
                    </li>

                    <!--
                    <li>
                        STAGE:
                        <i class="dashboard-tasks-list-icon primary icon-idea"></i>
                        <i class="dashboard-tasks-list-icon tertiary icon-content"></i>
                        <i class="dashboard-tasks-list-icon tertiary icon-alert"></i>
                        <i class="dashboard-tasks-list-icon tertiary icon-share"></i>
                    </li>
                    -->

                    <li>
                        <a :href="link"><strong>View Task</strong></a>
                    </li>
                </ul>
            </div>
            <div class="dashboard-tasks-cell cell-size-15">
                <span class="dashboard-tasks-text small" :class="{ active: active }">
                    {{ task.created_at_diff }}
                </span>
            </div>
        </div>
    `,

    props: [ 'task' ],

    created() {
        this.task.profile_image = this.task.user.profile_image || '/images/cl-avatar2.png';

        let dueDate = moment(this.task.due_date).format('MM/DD/YYYY');
        this.task.due_date = dueDate == 'Invalid date' ? false : dueDate;

        this.active = this.createdInLast10Minutes() ? 'active' : '';
        this.link = `/task/show/${this.task.id}`;
    },

    methods: {
        createdInLast10Minutes() {
            const currentTime = moment.utc().format('x');
            const timeAgo = moment(this.task.created_at).format('x');

            return (currentTime - timeAgo) <= 60 * 10 * 1000;
        }
    }
});