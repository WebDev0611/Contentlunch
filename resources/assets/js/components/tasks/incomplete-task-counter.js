'use strict';

Vue.component('incomplete-task-counter', {
    template: `
        <div class="dashboard-notification-box">
            <span class="dashboard-notification-box-count">
                <i class="icon-checklist"></i>
                <span id="incomplete-tasks">{{ incompleteTasksCount }}</span>
            </span>
            <span>Incomplete <br> Tasks</span>
        </div>
    `,

    data() {
        return {
            incompleteTasksCount: 0,
        };
    },

    created() {
        this.refresh();
    },

    methods: {
        refresh() {
            $.get('/api/tasks', { account_tasks: 1, })
                .then(response => this.incompleteTasksCount = response.data.length);
        }
    }
})