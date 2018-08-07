import Vue from 'vue';
import Vuex from 'vuex';

Vue.use(Vuex);

const state = {
    user: User,
    account: Account,
    accountPlan: AccountPlan,
    messagesByUser: {},
    unreadMessages: 0,
    ideas: [],
};

const mutations = {
    setIdeas: (state, payload) => state.ideas = payload,
    setUnreadMessages: (state, unread) => state.unreadMessages = unread,
    setMessages: (state, payload) => state.messagesByUser = payload,

    addMessage(state, message) {
        if (message.recipient_id == state.user.id) {
            let user = _(state.messagesByUser).find({ id: message.sender_id });
            user.messages.unshift(message);
        } else if (message.sender_id == state.user.id) {
            let user = _(state.messagesByUser).find({ id: message.recipient_id });
            user.messages.unshift(message);
        }
    },

    updateUnreadMessages(state) {
        let unreadMessages = state.messagesByUser.map(user => {
            return user.messages
                .filter(message => !message.read && message.recipient_id == User.id)
                .length;
        }).reduce((total, el) => total + el, 0);

        state.unreadMessages = unreadMessages;
    },

    markAllMessagesAsRead(state, userId) {
        let user = _(state.messagesByUser).find({ id: userId });

        user.messages.forEach(message => message.read = true);
    }
};

const actions = {
    fetchIdeas(context, payload) {
        $.get('/ideas').then(response => context.commit('setIdeas', response));
    },

    setMessages(context, payload) {
        context.commit('setMessages', payload);
        context.commit('updateUnreadMessages');
    },

    addMessageToConversation(context, payload) {
        context.commit('addMessage', payload);
        context.commit('updateUnreadMessages');
    },

    markAllMessagesAsRead(context, payload) {
        context.commit('markAllMessagesAsRead', payload);
        context.commit('updateUnreadMessages');
    }
}

const getters = {
    unreadMessages: state => state.unreadMessages,
    messagesByUser: state => state.messagesByUser,
    ideas: state => state.ideas,
    subscriptionType: state => state.accountPlan.slug,
};

export default new Vuex.Store({
    state,
    getters,
    actions,
    mutations,
});