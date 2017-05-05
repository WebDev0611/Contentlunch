import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const state = {
    unreadMessages: 0,
};

const mutations = {
    setUnreadMessages(state, unread) {
        console.log(unread);
        state.unreadMessages = unread;
    },
};

const actions = {
    setUnreadMessages: ({ commit }) => commit('setUnreadMessages'),
};

const getters = {
    unreadMessages: state => state.unreadMessages,
};

export default new Vuex.Store({
    state,
    getters,
    actions,
    mutations,
});