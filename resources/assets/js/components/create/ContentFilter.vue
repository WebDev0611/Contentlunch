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
                                <loading size='small' v-show='!stagesLoaded'></loading>
                                <div class="select select-small extend" v-show='stagesLoaded'>
                                    <select v-model='stage'>
                                        <option :value='null'>All Types</option>
                                        <option value="published">Published</option>
                                        <option value="ready_published">Ready to be Published</option>
                                        <option value="written">Being Written</option>
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
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-2 text-right">
                <div class="create-link-dropdown" v-show='loaded'>
                    <a href="#">FILTER CONTENT</a>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import Loading from '../Loading.vue';

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
                stagesLoaded: false,

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
                return this.stagesLoaded && this.campaignsLoaded && this.authorsLoaded;
            }
        }
    }
</script>