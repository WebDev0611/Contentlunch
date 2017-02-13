'use strict';

Vue.component('campaign-row', {
    template: `
        <div class="dashboard-tasks-container">
            <div class="dashboard-tasks-cell cell-size-5">
                <img :src="campaign.profile_image" alt="#" class="dashboard-tasks-img">
            </div>
            <div class="dashboard-tasks-cell cell-size-80">
                <h5 class="dashboard-tasks-title">
                    {{ campaign.title }}
                </h5>
                <ul class="dashboard-tasks-list">
                    <li>DUE ON: <strong>{{ campaign.due }}</strong></li>
                </ul>
            </div>
            <div class="dashboard-tasks-cell">
               SOMETHING
            </div>
        </div>
    `,

    props: [ 'campaign' ],

    created() {
        this.campaign.profile_image = this.campaign.user.profile_image || '/images/cl-avatar2.png';
    }
})