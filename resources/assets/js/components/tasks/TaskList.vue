<template>
    <div>
        <div class="dashboard-tasks-container" v-if="!tasks.length && loaded">
            <div class="dashboard-tasks-cell">
                <h5 class="dashboard-tasks-title">No tasks: </h5>
                <a href @click="openTaskModal">create one now</a>
            </div>
        </div>

        <task-row
            v-for='task in tasks'
            :task='task'
            :key='task.id'>
        </task-row>

        <loading v-if='!loaded'></loading>

        <load-more-button
            v-if='loaded'
            @click.native='fetchMoreTasks'
            :total-left='totalTasksLeft'>
        </load-more-button>
    </div>
</template>

<script>
    import TaskRow from './TaskRow.vue';
    import Loading from '../Loading.vue';
    import LoadMoreButton from '../LoadMoreButton.vue';

    export default {
        name: 'task-list',
        components: {
            TaskRow,
            Loading,
            LoadMoreButton,
        },

        props: [ 'userOnly' ],

        data() {
            return {
                loaded: false,
                tasks: [],
                totalTasks: 0,
                page: 1,
            };
        },

        created() {
            this.fetchTasks();
        },

        methods: {
            request() {
                let payload = {
                    account_tasks: this.userOnly ? 0 : 1,
                    page: this.page,
                };

                this.loaded = false;

                return $.get('/api/tasks', payload);
            },

            fetchTasks() {
                return this.request().then(response => {
                    this.tasks = response.data;
                    this.totalTasks = response.meta.total;
                    this.loaded = true;
                });
            },

            fetchMoreTasks() {
                this.page = this.page + 1;

                this.request().then(response => {
                    this.tasks = this.tasks.concat(response.data);
                    this.loaded = true;
                });
            },

            openTaskModal(event) {
                event.preventDefault();
                openTaskModal();
            },
        },

        computed: {
            totalTasksLeft() {
                return this.totalTasks - this.tasks.length;
            },
        },
    }
</script>