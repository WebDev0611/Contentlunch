<template>
    <div>
        <loading v-if='!loaded'></loading>
        <div class="dashboard-tasks-container" v-if="!tasks.length && loaded">
            <div class="dashboard-tasks-cell">
                <h5 class="dashboard-tasks-title">No tasks: </h5>
                <a href @click="openTaskModal">create one now</a>
            </div>
        </div>
        <task-row v-for='task in tasks' :task='task'></task-row>
    </div>
</template>

<script>
    import TaskRow from './TaskRow.vue';
    import Loading from '../Loading.vue';

    export default {
        name: 'task-list',

        props: [ 'userOnly' ],

        data() {
            return {
                loaded: false,
                tasks: [],
            };
        },

        created() {
            this.fetchTasks();
        },

        methods: {
            fetchTasks() {
                $.get('/api/tasks', { account_tasks: this.userOnly ? 0 : 1, })
                    .then(response => {
                        this.tasks = response.data;
                        this.loaded = true;
                    });
            },

            openTaskModal(event) {
                event.preventDefault();
                openTaskModal();
            }
        }
    }
</script>