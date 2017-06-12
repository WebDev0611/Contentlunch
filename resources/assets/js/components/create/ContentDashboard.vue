<template>
    <div>
        <content-filter :content-count='contentCount'></content-filter>
        <freemium-alert restriction='launch 5 content pieces'></freemium-alert>

        <div class="create-panel-container">
            <h4 class="create-panel-heading">
                <i class="icon-connect"></i>
                PUBLISHED
            </h4>

            <content-list stage='published'></content-list>
        </div>

        <div class="create-panel-container">
            <h4 class="create-panel-heading">
                <i class="icon-connect"></i>
                READY TO PUBLISH
            </h4>

            <content-list stage='ready'></content-list>
        </div>

        <div class="create-panel-container">
            <h4 class="create-panel-heading">
                <i class="icon-connect"></i>
                BEING WRITTEN / EDITED
            </h4>

            <content-list stage='written'></content-list>
        </div>

        <modal title='Launch Content' :show='showLaunchModal' @close='showLaunchModal = false'>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <p class="text-gray text-center">
                        Here are your content connections. To launch content, simply check the connections, click launch
                        and your content will be published right now!
                    </p>

                    <loading v-show='!connectionsLoaded'></loading>

                    <label v-for='connection in connections' class="checkbox-tag">
                        <input
                            class='connection-checkbox'
                            v-model='connectionsSelected'
                            :value='connection.id'
                            type="checkbox">
                        <span>{{ connection.name }}</span>
                    </label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <button class="button button-primary text-uppercase button-extend" @click='publishContent' v-show='!publishingContent'>
                        LAUNCH
                    </button>

                    <loading v-show='publishingContent'></loading>
                </div>
            </div>
        </modal>

        <modal title='Content Launched' :show='showContentLaunchedModal' @close='showContentLaunchedModal = false'>
            <div class="row">
                <div class="col-md-6 col-md-offset-3 text-center">
                    <i class="modal-icon-success icon-check-large"></i>
                    <div class="form-group">
                        <img src="/images/cl-avatar2.png" alt="#" class="create-image">
                        <h4></h4>
                    </div>
                    <p class="text-gray">IS NOW PUBLISHED TO:</p>
                    <div class="modal-social">
                        <span
                            v-for='name in connectionsPublished'
                            :class="[ 'icon-social-' + name ]">
                        </span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-md-offset-3">
                    <a @click.prevent='showContentLaunchedModal = false' href="#" class="button text-uppercase button-extend">
                        Go to Dashboard
                    </a>
                </div>
            </div>
        </modal>
    </div>
</template>

<script>
    import ContentList from './ContentList.vue';
    import bus from '../bus.js';

    export default {
        name: 'content-dashboard',

        components: {
            ContentList,
        },

        data() {
            return {
                showLaunchModal: false,
                showContentLaunchedModal: false,

                connections: [],
                connectionsSelected: [],
                connectionsPublished: [],
                contentSelected: null,

                connectionsLoaded: false,
                publishingContent: false,

                totals: {
                    published: 0,
                    ready: 0,
                    written: 0,
                }
            };
        },

        created() {
            bus.$on('launch-clicked', (content) => {
                this.fetchConnections();
                this.contentSelected = content;
                this.showLaunchModal = true;
            });

            bus.$on('contents-fetched', data => this.totals[data.stage] = data.total);
        },

        computed: {
            contentCount() {
                return this.totals.published + this.totals.ready + this.totals.written;
            },
        },

        methods: {
            fetchConnections() {
                this.connections = [];
                this.connectionsLoaded = false;

                $.get('/api/connections').then(response => {
                    this.connections = response.data.map(connection => {
                        connection.label_id = `connection-${connection.id}`;

                        return connection;
                    });
                    this.connectionsLoaded = true;
                });
            },

            publishContent() {
                this.publishRequest()
                    .then(this.showLaunchCompletedFeedback.bind(this))
                    .catch(this.showFeedbackError.bind(this));

            },

            showLaunchCompletedFeedback(response) {
                this.showLaunchModal = false;
                this.showContentLaunchedModal = true;
                this.connectionsPublished = response.published_connections;

                return response;
            },

            showFeedbackError(response) {
                this.showLaunchModal = false;
                swal('Error!', response.responseJSON.data, 'error');
            },

            publishRequest() {
                this.publishingContent = true;
                this.connectionsPublished = [];

                let connections = this.connectionsSelected.join(',');

                return $.get(`/content/multipublish/${this.contentSelected.id}?connections=${connections}`)
                    .then(response => {
                        this.publishingContent = false;
                        return response;
                    });
            }
        },
    }
</script>