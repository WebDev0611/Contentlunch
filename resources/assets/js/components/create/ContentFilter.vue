<template>
    <div class="panel-container-options white">
        <div class="row">
            <div class="col-md-2">
                <label class="select-horizontal-label">{{ contentCount }} Content Items</label>
            </div>
            <div class="col-md-8">
                <div class="row">
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <label class="select-horizontal-label">Show:</label>
                            </div>
                            <div class="col-md-8">
                                <div class="select select-small extend">
                                    <select v-model='stage'>
                                        <option :value='null'>All Types</option>
                                        <option value="3">Published</option>
                                        <option value="2">Ready</option>
                                        <option value="1">Writing</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <label class="select-horizontal-label">By:</label>
                            </div>
                            <div class="col-md-8">
                                <loading size='small' v-show='!authorsLoaded'></loading>
                                <div class="select select-small extend" v-show='authorsLoaded'>
                                    <select v-model='author'>
                                        <option :value="null">Any one</option>
                                        <option v-for='author in authors' :value="author.id">
                                            {{ author.name }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="row">
                            <div class="col-md-3 text-right">
                                <label class="select-horizontal-label">Campaign:</label>
                            </div>
                            <div class="col-md-8">
                                <loading size='small' v-show='!campaignsLoaded'></loading>
                                <div class="select select-small extend" v-show='campaignsLoaded'>
                                    <select v-model='campaign'>
                                        <option :value="null">All</option>
                                        <option v-for='campaign in campaigns' :value="campaign.id">
                                            {{ campaign.title }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 text-right">
                <div class="create-link-dropdown" v-show='loaded'>
                    <a :href="filterUrl()">FILTER CONTENT</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Loading from '../Loading.vue';
    import URLSearchParams from 'url-search-params';

    export default {
        name: 'content-filter',

        components: {
            Loading,
        },

        props: [ 'contentCount' ],

        data() {
            return {
                stage: null,
                stages: [],

                campaign: null,
                campaigns: [],
                campaignsLoaded: false,

                author: null,
                authors: [],
                authorsLoaded: false,
            };
        },

        computed: {
            loaded() {
                return this.campaignsLoaded && this.authorsLoaded;
            }
        },

        created() {
            this.fetchCampaigns();
            this.fetchCollaborators();
            this.setDefaultParameters();
        },

        methods: {
            setDefaultParameters() {
                let params = new URLSearchParams(location.search.slice(1));

                this.stage = params.get('stage') || this.stage;
                this.campaign = params.get('campaign') || this.campaign;
                this.author = params.get('author') || this.author;
            },

            fetchCampaigns() {
                $.get('/api/campaigns').then(response => {
                    this.campaigns = response.data;
                    this.campaignsLoaded = true;
                })
            },

            fetchCollaborators() {
                $.get('/api/account/members').then(response => {
                    this.authors = response.filter(author => !author.is_guest);
                    this.authorsLoaded = true;
                });
            },

            filterUrl() {
                let parameters = $.param({
                    stage: this.stage,
                    campaign: this.campaign,
                    author: this.author
                });

                return `/content?${parameters}`;
            }
        }
    }
</script>