<template>
    <div>
        <div class="dashboard-ideas-cell cell-size-5">
            <div class="dashboard-tasks-img-wrapper">
                <img :src="imageProfile" alt="#" class="dashboard-tasks-img">
            </div>
        </div>
        <div class="dashboard-members-cell cell-size-75">
            <p class="dashboard-ideas-text">{{ content.title }}</p>
            STAGE:
            <content-stage-icon :content-stage='contentStage'></content-stage-icon>
        </div>
        <div class="dashboard-members-cell cell-size-20">
            <span class="dashboard-ideas-text small" style='float:right' title='This user has 3 assigned task(s)'>
                <i class="icon-checklist"></i>
                123
            </span>
        </div>
    </div>
</template>

<script>
    import ContentStageIcon from './ContentStageIcon.vue';

    export default {
        name: 'content-list-item',

        components: {
            ContentStageIcon,
        },

        props: [ 'content' ],

        computed: {
            imageProfile() {
                let defaultAvatar = '/images/cl-avatar2.png';

                return this.content.author
                    ? this.content.author.profile_image || defaultAvatar
                    : defaultAvatar;
            },

            contentStage() {
                return this.content.content_status_id;
            }
        }
    }
</script>