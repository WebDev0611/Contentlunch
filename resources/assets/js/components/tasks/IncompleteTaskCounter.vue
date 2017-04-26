<template>
    <div class="dashboard-notification-box">
            <span class="dashboard-notification-box-count">
                <i class="icon-checklist"></i>
                <span id="incomplete-tasks">{{ incompleteTasksCount }}</span>
            </span>
        <span>Incomplete <br> Tasks</span>
    </div>
</template>

<script>
    export default {
        name: 'incomplete-task-counter',

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
    }
</script>
