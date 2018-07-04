import Vue from 'vue';
import store from './store.js';

// General Components
Vue.component('loading',                    require('./components/Loading.vue'));
Vue.component('avatar',                     require('./components/Avatar.vue'));
Vue.component('freemium-alert',             require('./components/FreemiumAlert.vue'));
Vue.component('messaging-system',           require('./components/messaging-system/MessagingSystem.vue'));
Vue.component('open-message-bar-button',    require('./components/messaging-system/OpenMessageBarButton.vue'));
Vue.component('alert',                      require('./components/Alert.vue'));
Vue.component('modal',                      require('./components/Modal.vue'));

// Collaborate Module
Vue.component('collaborate-module', require('./components/collaborate/CollaborateModule.vue'));

// Dashboard
Vue.component('incomplete-task-counter',    require('./components/tasks/IncompleteTaskCounter.vue'));
Vue.component('task-list',                  require('./components/tasks/TaskList.vue'));
Vue.component('campaign-list',              require('./components/campaigns/CampaignList.vue'));
Vue.component('content-orders-counter',     require('./components/orders/ContentOrdersCounter.vue'));
Vue.component('guests-invite-modal',        require('./components/guests/InviteModal.vue'));
Vue.component('guests-list',                require('./components/guests/GuestList.vue'));
Vue.component('content-messages',           require('./components/messaging-system/ContentMessages.vue'));
Vue.component('activity-feed',              require('./components/dashboard/ActivityFeed.vue'));
Vue.component('recent-content-list',        require('./components/dashboard/RecentContentList.vue'));
Vue.component('recent-ideas-list',          require('./components/dashboard/RecentIdeasList.vue'));

// Content Orders
Vue.component('content-order-comment',      require('./components/orders/ContentOrderComment.vue'));
Vue.component('content-order-comment-list', require('./components/orders/ContentOrderCommentList.vue'));
Vue.component('content-orders-list',        require('./components/orders/ContentOrdersList.vue'));
Vue.component('content-order-item',         require('./components/orders/ContentOrderItem.vue'));
Vue.component('content-orders-filter',      require('./components/orders/ContentOrdersFilter.vue'));

// Create Module
Vue.component('content-filter',     require('./components/create/ContentFilter.vue'));
Vue.component('campaign-filter',    require('./components/create/CampaignFilter.vue'));
Vue.component('content-dashboard',  require('./components/create/ContentDashboard.vue'));

// Plan Module
Vue.component('ideas', require('./components/plan/Ideas.vue'));

// Review Module
Vue.component('content-feedback-counter',       require('./components/reviews/ContentFeedbackCounter.vue'));
Vue.component('content-feedback-comment-list',  require('./components/reviews/contentFeedbackCommentList.vue'));
Vue.component('content-feedback-comment',       require('./components/reviews/ContentFeedbackComment.vue'));

new Vue({
    el: '#root',
    store,
});
