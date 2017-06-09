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
            <recent-content-list-item
                v-for='content in contents'
                :content='content'
                :key='content.id'>
            </recent-content-list-item>

            <loading v-show='!loaded'></loading>

            <load-more-button
                v-show='loaded'
                @click.native='fetchContents'
                :total-left='totalContentsLeft'>
            </load-more-button>
        </div>
    </div>
</template>

<script>
    import Loading from '../Loading.vue';
    import RecentContentListItem from './RecentContentListItem.vue';
    import LoadMoreButton from '../LoadMoreButton.vue';

    export default {
        name: 'recent-content-list',

        components: {
            Loading,
            LoadMoreButton,
            RecentContentListItem,
        },

        data() {
            return {
                contents: [],
                loaded: true,
                total: 0,
                page: 1,
            };
        },

        created() {
            this.fetchContents().then(response => this.loading = false);
        },

        methods: {
            request() {
                let payload = {
                    page: this.page++,
                    include: 'user',
                };

                this.loaded = false;

                return $.get('/api/contents', payload).then(response => {
                    this.loaded = true;
                    return response;
                })
            },

            fetchContents() {
                return this.request().then(response => {
                    this.contents = this.contents.concat(response.data);
                    this.total = response.meta.total;
                });
            },
        },

        computed: {
            totalContentsLeft() {
                return this.total - this.contents.length;
            },
        },
    }
</script>