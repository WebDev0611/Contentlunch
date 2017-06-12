<template>
    <div>
        <alert type='info' v-show='!contents.length && loaded'>
            No Published Content at this moment.
        </alert>

        <content-list-item
            v-for='content in contents'
            :content='content'
            :key='content.id'>
        </content-list-item>

        <loading v-show='!loaded'></loading>

        <load-more-button
            v-show='loaded'
            @click.native='fetchContents'
            :total-left='contentsLeft'>
        </load-more-button>
    </div>
</template>

<script>
    import LoadMoreButton from '../LoadMoreButton.vue';
    import ContentListItem from './ContentListItem.vue';
    import bus from '../bus.js';

    export default {
        name: 'content-list',

        components: {
            LoadMoreButton,
            ContentListItem,
        },

        props: [ 'stage' ],

        data() {
            return {
                contents: [],
                loaded: false,
                total: 0,
                page: 1,
                authorFilter: null,
                campaignFilter: null,
                stageFilter: null,
            }
        },

        created() {
            this.fetchContents();

            bus.$on('filter', filters => {
                this.authorFilter = filters.author;
                this.campaignFilter = filters.campaign;
                this.stageFilter = filters.stage;
                this.page = 1;
                this.contents = [];

                this.fetchContents();
            });
        },

        methods: {
            url() {
                let url = '/api/contents/';

                return url + this.stage;
            },

            request() {
                let payload = {
                    include: 'user',
                    page: this.page++,
                    author: this.authorFilter,
                    campaign: this.campaignFilter,
                    stage: this.stageFilter,
                };

                this.loaded = false;

                return $.get(this.url(), payload).then(response => {
                    this.loaded = true;
                    return response;
                })
            },

            fetchContents() {
                return this.request().then(response => {
                    this.contents = this.contents.concat(response.data);
                    this.total = response.meta.total;

                    bus.$emit('contents-fetched', {
                        contents: this.contents,
                        total: this.total,
                        stage: this.stage,
                    });
                });
            }
        },

        computed: {
            contentsLeft() {
                return this.total - this.contents.length;
            }
        }
    }
</script>