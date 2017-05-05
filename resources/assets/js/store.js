import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const state = {
    user: User,
    messagesByUser: {},
    unreadMessages: 0,
};

const mutations = {
    setUnreadMessages: (state, unread) => state.unreadMessages = unread,
};

const getters = {
    unreadMessages: state => state.unreadMessages,
};

export default new Vuex.Store({
    state,
    getters,
    mutations,
});