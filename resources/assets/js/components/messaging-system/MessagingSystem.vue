<template>
    <div class="sidemodal" id='message-modal'>
        <div class="sidemodal-header">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="sidemodal-header-title">Team Communication</h4>
                    <small v-if='otherMembersOnline > 0'>{{ otherMembersOnline }} online</small>
                </div>
                <div class="col-md-6 text-right" id="task-menu">
                    <button class="sidemodal-close normal-flow" @click='closeModal'>
                        <i class="icon-remove"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="sidemodal-container nopadding">
            <div class='team-members-list'>
                <messages-team-member
                    v-for='user in users'
                    @messages:user-selected='selectTeamMember'
                    :user='user'
                    :online='user.online'
                    :key='user.id'>
                </messages-team-member>
            </div>

            <div class="conversation" :class="{ 'in': selectedUser !== null }">
                <message-list-header
                    @messages:close-conversation='closeConversation'
                    :selected='selectedUser'>
                </message-list-header>

                <input type="text" v-model='message' @keyup.enter='sendMessage' class='messages-list-input'>

                <message-list
                    :messages='users'
                    :selected='selectedUser'>
                </message-list>
            </div>
        </div>
    </div>
</template>

<script>
    import MessagesTeamMember from './MessagesTeamMember.vue';
    import MessageList from './MessageList.vue';
    import MessageListHeader from './MessageListHeader.vue';

    export default {
        name: 'messaging-system',
        components: {
            MessagesTeamMember,
            MessageList,
            MessageListHeader,
        },

        data() {
            return {
                users: [],
                message: '',
                channel: null,
                otherMembersOnline: 0,
                selectedUser: null,
            }
        },

        created() {
            this.fetchMessages()
                .then(this.configureChannel.bind(this))
                .then(this.setMessages);
        },

        methods: {
            setMessages(response) {
                let messages = response.data
                    .filter(user => user.id != this.$store.state.user.id);

                this.$store.dispatch('setMessages', messages);
            },

            configureChannel(response) {
                this.channel = pusher.subscribe(response.channel);

                this.channel.bind('new-message', this.addMessageToConversation.bind(this));
                this.channel.bind('pusher:member_added', this.updateOnlineCount.bind(this));
                this.channel.bind('pusher:member_removed', this.updateOnlineCount.bind(this));
                this.channel.bind('pusher:subscription_succeeded', members => {
                    this.updateOnlineCount();
                });

                return response;
            },

            addMessageToConversation(data) {
                let message = data.message;
                let user = null;

                if (message.recipient_id == User.id) {
                    user = _(this.users).find({ id: message.sender_id });
                    user.messages.unshift(message);
                } else if (message.sender_id == User.id) {
                    user = _(this.users).find({ id: message.recipient_id });
                    user.messages.unshift(message);
                }

                this.updateUnreadMessages();
            },

            updateOnlineCount() {
                this.otherMembersOnline = this.channel.members.count - 1;
                this.usersOnline = [];

                this.users.forEach(user => {
                    let userIsOnline = this.channel.members.get(user.id);

                    user.online = userIsOnline !== null;

                    if (userIsOnline) {
                        this.usersOnline.push(user.id);
                    }
                });
            },

            closeModal() {
                $(this.$el).removeClass('in');
            },

            fetchMessages() {
                return $.get('/api/messages').then(response => {
                    this.users = response.data.filter(user => user.id !== User.id);
                    this.updateUnreadMessages();

                    return response;
                });
            },

            updateUnreadMessages() {
                let unreadMessages = this.users.map(user => {
                        return user.messages
                            .filter(message => !message.read && message.recipient_id == User.id)
                            .length;
                    })
                    .reduce((total, el) => total + el, 0);

                this.$store.commit('setUnreadMessages', unreadMessages);
            },

            selectTeamMember(user) {
                this.selectedUser = user;
            },

            closeConversation() {
                this.selectedUser = null;
            },

            sendMessage() {
                let url = `/api/messages/${this.selectedUser.id}`;
                $.post(url, { body: this.message });
                this.message = '';
            },
        }
    }
</script>