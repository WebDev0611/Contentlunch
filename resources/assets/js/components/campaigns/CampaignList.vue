<template>
    <div>
        <loading v-if='!loaded'></loading>
        <div class="dashboard-tasks-container" v-if="!campaigns.length && loaded">
            <div class="dashboard-tasks-cell">
                <h5 class="dashboard-tasks-title">No campaigns: </h5>
                <a href="/campaign">create one now</a>
            </div>
        </div>
        <campaign-row v-for='campaign in campaigns' :campaign="campaign"></campaign-row>
    </div>
</template>

<script>
    import CampaignRow from './CampaignRow.vue';
    import Loading from '../Loading.vue';

    export default {
        name: 'campaign-list',

        data() {
            return {
                campaigns: [],
                loaded: false,
            }
        },

        created() {
            this.fetchCampaigns().then(response => {
                this.campaigns = response.data;
                this.loaded = true;
            });
        },

        methods: {
            fetchCampaigns() {
                return $.get('/api/campaigns');
            }
        }
    }
</script>