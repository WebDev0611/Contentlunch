<template>
    <div class="sidemodal" id='message-modal'>
        <div class="sidemodal-header">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="sidemodal-header-title">Team Communication</h4>
                    <small v-show='otherMembersOnline > 0'>{{ otherMembersOnline }} online</small>
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
                    @click='selectedTeamMember(user)'
                    :user='user'
                    :messages='0'>
                </messages-team-member>
            </div>

            <input type="text" v-model='message' @keyup.enter='sendMessage' class='messages-list-input'>

            <message-list :messages='messages' :selected='selectedUser'></message-list>
        </div>
    </div>
</template>

<script>
    import MessagesTeamMember from './MessagesTeamMember.vue';
    import MessageList from './MessageList.vue';

    export default {
        name: 'messaging-system',
        components: {
            MessagesTeamMember,
            MessageList,
        },

        data() {
            return {
                users: [],
                messages: [],
                message: '',
                channel: null,
                otherMembersOnline: 0,
                selectedUser: null,
            }
        },

        ready() {
            this.fetchTeamMembers();

            this.fetchMessages().then(this.configureChannel.bind(this));
        },

        methods: {
            configureChannel(response) {
                console.log(`Configuring channel ${response.channel}`);
                this.channel = pusher.subscribe(response.channel);

                this.channel.bind('new-message', this.addMessageToConversation.bind(this));
                this.channel.bind('pusher:member_added', this.updateOnlineCount.bind(this));
                this.channel.bind('pusher:member_removed', this.updateOnlineCount.bind(this));
                this.channel.bind('pusher:subscription_succeeded', members => {
                    this.updateOnlineCount();
                });

                console.log('Channel configured.');
            },

            addMessageToConversation(data) {
                let message = data.message;
                let user = null;

                if (message.recipient_id == User.id) {
                    user = _(this.messages).find({ id: message.sender_id });
                    user.messages.unshift(message);
                } else if (message.sender_id == User.id) {
                    user = _(this.messages).find({ id: message.recipient_id });
                    user.messages.unshift(message);
                }
            },

            updateOnlineCount() {
                this.otherMembersOnline = this.channel.members.count - 1;
            },

            closeModal() {
                $(this.$el).removeClass('in');
            },

            fetchMessages() {
                return $.get('/api/messages').then(response => {
                    this.messages = response.data;

                    return response;
                });
            },

            fetchTeamMembers() {
                $.get('/api/account/members').then(response => {
                    this.users = response.filter(user => user.id !== User.id);
                })
            },

            selectedTeamMember(user) {
                this.selectedUser = user;
            },

            sendMessage() {
                let url = `/api/messages/${this.selectedUser.id}`;
                $.post(url, { body: this.message });
                this.message = '';
            }
        }
    }
</script>