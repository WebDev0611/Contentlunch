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
    SET_MESSAGES: (state, payload) => state.messagesByUser = payload,
};

const actions = {
    setMessages: (context, payload) => context.commit('SET_MESSAGES', payload),
}

const getters = {
    unreadMessages: state => state.unreadMessages,
};

export default new Vuex.Store({
    state,
    getters,
    actions,
    mutations,
});