<template>
    <div>
        <loading v-if='!loaded'></loading>
        <div class="dashboard-tasks-container" v-if="!campaigns.length && loaded">
            <div class="dashboard-tasks-cell" v-if='!user.is_guest'>
                <h5 class="dashboard-tasks-title">No campaigns: </h5>
                <a href="/campaign" >create one now</a>
            </div>
            <div class="dashboard-tasks-cell" v-if='user.is_guest'>
                <h5 class="dashboard-tasks-title">
                    No campaigns found.
                </h5>
            </div>
        </div>
        <campaign-row
            v-for='campaign in campaigns'
            :campaign="campaign"
            :key="campaign.id">
        </campaign-row>
    </div>
</template>

<script>
    import CampaignRow from './CampaignRow.vue';
    import Loading from '../Loading.vue';
    import { mapState } from 'vuex';

    export default {
        name: 'campaign-list',
        components: {
            CampaignRow,
        },

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
        },

        computed: mapState({
            user(state) {
                return state.user;
            }
        }),
    }
</script>