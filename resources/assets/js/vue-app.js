"use strict";

function createVueApp() {
    const Vue = require('vue');

    Vue.component('incomplete-task-counter', require('./components/tasks/IncompleteTaskCounter.vue'));
    Vue.component('task-list', require('./components/tasks/TaskList.vue'));
    Vue.component('campaign-list', require('./components/campaigns/CampaignList.vue'));
    Vue.component('loading', require('./components/Loading.vue'));
    Vue.component('collaborate-module', require('./components/collaborate/CollaborateModule.vue'));
    Vue.component('content-orders-counter', require('./components/orders/ContentOrdersCounter.vue'));


    new Vue({ el: '#root' });
}

function shouldVueLoad() {
    const path = document.location.pathname;
    const allowedPaths = [ '/', '/home', '/collaborate' ];

    return !_.isEmpty(allowedPaths.filter(p => p === path));
}

if (shouldVueLoad()) {
    createVueApp();
}