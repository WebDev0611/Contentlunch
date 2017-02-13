'use strict';

Vue.component('campaign-list', {
    template: `
        <div>
            <loading v-if='!loaded'></loading>
            <campaign-row v-for='campaign in campaigns' :campaign="campaign"></campaign-row>
        </div>
    `,

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
});