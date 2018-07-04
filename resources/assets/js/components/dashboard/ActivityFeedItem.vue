<template>
    <div class="plan-activity-box-container">
        <div class="plan-activity-box-img">
            <div class="avatar-img-wrapper">
                <img :src="activity.user.profile_image" class='avatar-img' alt="#">
            </div>
        </div>
        <div class="plan-activity-box">
            <span class="plan-activity-title">
                {{ activity.user.name }} {{ activity.action }} the
                <a :href="activity.subject_link">{{ activity.subject_name }}</a> {{ activity.subject_type }}.
            </span>
            <p class="dashboard-ideas-text small">{{ activity.date }}</p>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'activity-feed-item',

        props: [ 'activity' ],
    }
</script>