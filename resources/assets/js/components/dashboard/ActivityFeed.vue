<template>
    <div class="panel">
        <div class="panel-header">
            <h4 class="panel-sidebar-title-secondary">
                Activity Feed
            </h4>
        </div>
        <div class="panel-container">
            <activity-feed-item
                v-for='activity in activities'
                :activity='activity'
                :key='activity.id'>
            </activity-feed-item>
        </div>

        <loading v-show='!loaded'></loading>

        <load-more-button
            v-show='loaded'
            @click.native='fetchActivityFeed'
            :total-left='activitiesLeft'>
        </load-more-button>
    </div>
</template>

<script>
    import ActivityFeedItem from './ActivityFeedItem.vue';
    import Loading from '../Loading.vue';
    import LoadMoreButton from '../LoadMoreButton.vue';

    export default {
        name: 'activity-feed',
        components: {
            ActivityFeedItem,
            Loading,
            LoadMoreButton,
        },

        data() {
            return {
                activities: [],
                loaded: false,
                page: 1,
            };
        },

        created() {
            this.fetchActivityFeed();
        },

        methods: {
            request() {
                let payload = {
                    page: this.page++,
                    include: 'user',
                    total: 0,
                };

                this.loaded = false;

                return $.get('/api/activity_feed', payload).then(response => {
                    this.loaded = true;
                    return response;
                });
            },

            fetchActivityFeed() {
                return this.request().then(response => {
                    this.activities = this.activities.concat(response.data);
                    this.total = response.meta.total;
                });
            },
        },

        computed: {
            activitiesLeft() {
                return this.total - this.activities.length;
            },
        }
    }
</script>