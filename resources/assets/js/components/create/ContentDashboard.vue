<template>
    <div>
        <freemium-alert restriction='launch 5 content pieces'></freemium-alert>

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
                    <button class="button button-primary text-uppercase button-extend">
                        LAUNCH
                    </button>
                </div>
            </div>
        </modal>

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
                connectionsLoaded: false,
                connections: [],
                connectionsSelected: [],
                contentSelected: null,
            };
        },

        created() {
            bus.$on('launch-clicked', (content) => {
                this.fetchConnections();
                this.contentSelected = content;
                this.showLaunchModal = true;
            });
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
        },
    }
</script>