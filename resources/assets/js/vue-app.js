import Vue from 'vue';
import store from './store.js';

Vue.component('incomplete-task-counter', require('./components/tasks/IncompleteTaskCounter.vue'));
Vue.component('task-list', require('./components/tasks/TaskList.vue'));
Vue.component('campaign-list', require('./components/campaigns/CampaignList.vue'));
Vue.component('loading', require('./components/Loading.vue'));
Vue.component('collaborate-module', require('./components/collaborate/CollaborateModule.vue'));
Vue.component('messaging-system', require('./components/messaging-system/MessagingSystem.vue'));
Vue.component('open-message-bar-button', require('./components/messaging-system/OpenMessageBarButton.vue'));
Vue.component('content-orders-counter', require('./components/orders/ContentOrdersCounter.vue'));
Vue.component('guests-invite-modal', require('./components/guests/InviteModal.vue'));
Vue.component('guests-list', require('./components/guests/GuestList.vue'));
Vue.component('content-order-comment', require('./components/orders/ContentOrderComment.vue'));
Vue.component('content-order-comment-list', require('./components/orders/ContentOrderCommentList.vue'));

new Vue({
    el: '#root',
    store,
});