"use strict";

function createVueApp() {
    const Vue = require('vue');

    Vue.component('incomplete-task-counter', require('./components/tasks/IncompleteTaskCounter.vue'));
    Vue.component('task-list', require('./components/tasks/TaskList.vue'));
    Vue.component('task-row', require('./components/tasks/TaskRow.vue'));
    Vue.component('campaign-list', require('./components/campaigns/CampaignList.vue'));
    Vue.component('campaign-row', require('./components/campaigns/CampaignRow.vue'));
    Vue.component('loading', require('./components/Loading.vue'));

    new Vue({ el: '#root' });
}

function shouldVueLoad() {
    const path = document.location.pathname;
    const allowedPaths = [ '/', '/home' ];

    return !_.isEmpty(allowedPaths.filter(p => p === path));
}

if (shouldVueLoad()) {
    createVueApp();
}