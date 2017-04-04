'use strict';

Vue.component('campaign-row', {
    template: `
        <div class="dashboard-tasks-container">
            <div class="dashboard-tasks-cell cell-size-5">
                <div class="dashboard-tasks-img-wrapper">
                    <img :src="campaign.profile_image" alt="#" class="dashboard-tasks-img">
                </div>
            </div>
            <div class="dashboard-tasks-cell cell-size-55">
                <h5 class="dashboard-tasks-title">
                    <a :href="link">
                        {{ campaign.title }}
                    </a>
                </h5>
                <ul class="dashboard-tasks-list">
                    <li>UPDATED: <strong>{{ campaign.updated_at_diff.toUpperCase() }}</strong></li>
                </ul>
            </div>
            <div class="dashboard-tasks-cell cell-size-20">
                <ul class="dashboard-tasks-list">
                    <li>
                        START DATE: <br />
                        <strong>{{ campaign.started.toUpperCase() }}</strong>
                    </li>
                </ul>
            </div>
            <div class="dashboard-tasks-cell cell-size-20">
                <div class="dashboard-tasks-list">
                    <li>
                        END DATE: <br />
                        <strong>{{ campaign.ending.toUpperCase() }}</strong>
                    </li>
                </div>
            </div>
            <div></div>
        </div>
    `,

    props: [ 'campaign' ],

    created() {
        this.campaign.profile_image = this.campaign.user.profile_image || '/images/cl-avatar2.png';
        this.link = `/campaign/${this.campaign.id}`;
    }
})