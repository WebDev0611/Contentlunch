<template>
    <div class="panel">
        <div class="panel-header">
            <h4 class="panel-sidebar-title-secondary">
                Activity Feed
            </h4>
        </div>
        <div class="panel-container">
            <loading v-show='loading'></loading>
            <activity-feed-item
                v-for='activity in activities'
                :activity='activity'
                :key='activity.id'>
            </activity-feed-item>
        </div>
    </div>
</template>

<script>
    import ActivityFeedItem from './ActivityFeedItem.vue';
    import Loading from '../Loading.vue';

    export default {
        name: 'activity-feed',
        components: {
            ActivityFeedItem,
            Loading,
        },

        data() {
            return {
                activities: [],
                loading: true,
            };
        },

        created() {
            this.fetchActivityFeed();
        },

        methods: {
            fetchActivityFeed() {
                $.get('/api/activity_feed').then(response => {
                    this.activities = response.data;
                    this.loading = false;
                });
            },
        }
    }
</script>