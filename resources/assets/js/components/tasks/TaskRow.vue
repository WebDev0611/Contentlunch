<template>
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

                <li v-if='contentStage > 0'>
                    STAGE:
                    <content-stage-icon :content-stage='contentStage'></content-stage-icon>
                </li>

                <li v-if='contentStage > 0'>
                    <a :href="contentLink"><strong>View Content</strong></a>
                </li>
            </ul>
        </div>
        <div class="dashboard-tasks-cell cell-size-15">
            <span class="dashboard-tasks-text small" :class="{ active: active }">
                {{ task.created_at_diff }}
            </span>
        </div>
    </div>
</template>

<script>
    import ContentStageIcon from '../dashboard/ContentStageIcon.vue';

    export default {
        name: 'task-row',

        props: [ 'task' ],

        components: {
            ContentStageIcon,
        },

        created() {
            this.task.profile_image = this.task.user.profile_image || '/images/cl-avatar2.png';

            let dueDate = moment(this.task.due_date).format('MM/DD/YYYY');
            this.task.due_date = dueDate === 'Invalid date' ? false : dueDate;

            this.active = this.createdInLast10Minutes() ? 'active' : '';
        },

        methods: {
            createdInLast10Minutes() {
                const currentTime = moment.utc().format('x');
                const timeAgo = moment(this.task.created_at).format('x');

                return (currentTime - timeAgo) <= 60 * 10 * 1000;
            },
        },

        computed: {
            content() {
                return this.task.contents ? this.task.contents.data[0] : null;
            },

            contentStage() {
                return this.content ? this.content.content_status_id : 0;
            },

            contentLink() {
                return this.content ? `/edit/${this.content.id}` : '';
            },

            link() {
                return `/task/show/${this.task.id}`;
            },
        }
    }
</script>