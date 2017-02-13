'use strict';

Vue.component('tasks-list', {
    template: `
        <div>
            <div class="dashboard-tasks-container">
                <div class="dashboard-tasks-cell">
                    <h5 class="dashboard-tasks-title">No tasks: </h5>
                    <a href @click="openTaskModal">create one now</a>
                </div>
            </div>
            <task-row v-for='task in tasks' :task='task'></task-row>
        </div>
    `,

    props: [ 'userOnly' ],

    data() {
        return {
            loaded: false,
            tasks: [],
        };
    },

    created() {
        this.fetchTasks().then(response => {
            this.tasks = response.data;
            this.loaded = true;
        });
    },

    methods: {
        fetchTasks() {
            return $.get('/api/tasks', {
                account_tasks: this.userOnly ? 1 : 0,
            });
        },

        openTaskModal(event) {
            event.preventDefault();
            openTaskModal();
        }
    }
})