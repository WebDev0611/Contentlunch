<template>
    <div class="panel-container-options white">
        <div class="row">
            <div class="col-md-2">
                <label class="select-horizontal-label">{{ campaignCount }} Campaigns</label>
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
                                        <option value="active">Active</option>
                                        <option value="in-preparation">In Preparation</option>
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

                </div>
            </div>
            <div class="col-md-2 text-right">
                <div class="create-link-dropdown" v-show='loaded'>
                    <a :href="filterUrl()">FILTER CAMPAIGNS</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Loading from '../Loading.vue';
    import URLSearchParams from 'url-search-params';

    export default {
        name: 'campaign-filter',

        components: {
            Loading,
        },

        props: [ 'campaignCount' ],

        data() {
            return {
                stage: null,
                stages: [],

                author: null,
                authors: [],
                authorsLoaded: false,
            };
        },

        computed: {
            loaded() {
                return this.authorsLoaded;
            }
        },

        created() {
            this.fetchCollaborators();
            this.setDefaultParameters();
        },

        methods: {
            setDefaultParameters() {
                let params = new URLSearchParams(location.search.slice(1));

                this.stage = params.get('stage') || this.stage;
                this.author = params.get('author') || this.author;
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
                    author: this.author
                });

                return `/content/campaigns?${parameters}`;
            }
        }
    }
</script>