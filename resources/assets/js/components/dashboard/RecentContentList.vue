<template>
    <div class="panel">
        <div class="panel-header">
            <h4 class="panel-sidebar-title-secondary">
                Recent Content
                <a href="/content">
                    See All
                    <i class="icon-arrow-right"></i>
                </a>
            </h4>
        </div>
        <div class="panel-container nopadding">
            <loading v-show='loading'></loading>
            <recent-content-list-item
                v-for='content in contents'
                :content='content'
                :key='content.id'>
            </recent-content-list-item>
        </div>
    </div>
</template>

<script>
    import Loading from '../Loading.vue';
    import RecentContentListItem from './RecentContentListItem.vue';

    export default {
        name: 'recent-content-list',

        components: {
            Loading,
            RecentContentListItem,
        },

        data() {
            return {
                contents: [],
                loading: true,
            };
        },

        created() {
            this.fetchContents().then(response => this.loading = false);
        },

        methods: {
            fetchContents() {
                return $.get('/api/contents').then(response => {
                    this.contents = response.data;

                    return response;
                });
            },
        },
    }
</script>