<template>
    <div>
        <div class="dashboard-ideas-cell cell-size-5">
            <div class="dashboard-tasks-img-wrapper">
                <avatar :user='content.user'></avatar>
            </div>
        </div>
        <div class="dashboard-members-cell cell-size-75">
            <a :href="'/edit/' + content.id" title="Edit content">
                <p class="dashboard-ideas-text">{{ content.title }}</p>
            </a>
            <span class="dashboard-members-text small">
                DUE IN: <b>{{ humanDueDate }}</b>
            </SPAN>
            <span class="dashboard-members-text small">
                STAGE:
                <content-stage-icon :content-stage='contentStage'></content-stage-icon>
            </span>
        </div>
    </div>
</template>

<script>
    import ContentStageIcon from './ContentStageIcon.vue';

    export default {
        name: 'recent-content-list-item',

        components: {
            ContentStageIcon,
        },

        props: [ 'content' ],

        computed: {
            contentStage() {
                return this.content.content_status_id;
            },

            humanDueDate() {
                return this.content.due_date_human.replace('from now', '')
            }
        }
    }
</script>

<style scoped>
    p.dashboard-ideas-text {
        margin-bottom: 6px;
        font-weight: 600;
    }
    .dashboard-members-text.small {
        display: inline-block;
        text-transform: uppercase;
        width: 49%;
    }
    @media (max-width: 1494px) {
        .dashboard-members-text.small {
            width: 100%;
        }
    }
</style>