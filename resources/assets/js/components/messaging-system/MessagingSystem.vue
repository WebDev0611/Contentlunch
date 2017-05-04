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
                    :key='user.id'
                    :messages='0'>
                </messages-team-member>
            </div>

            <div class="conversation" :class="{ 'in': selectedUser !== null }">
                <message-list-header
                    @messages:close-conversation='closeConversation'
                    :selected='selectedUser'>
                </message-list-header>

                <input type="text" v-model='message' @keyup.enter='sendMessage' class='messages-list-input'>

                <message-list
                    :messages='messages'
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
                messages: [],
                message: '',
                channel: null,
                otherMembersOnline: 0,
                selectedUser: null,
            }
        },

        created() {
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

            selectTeamMember(user) {
                console.log('Selecting team member: ' + user.name);
                this.selectedUser = user;
            },

            closeConversation() {
                console.log('Closing conversation.');
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