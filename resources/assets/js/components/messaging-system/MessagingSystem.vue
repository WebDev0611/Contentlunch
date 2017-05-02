<template>
    <div class="sidemodal" id='message-modal'>
        <div class="sidemodal-header">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="sidemodal-header-title">Team Communication</h4>
                    <small v-show='membersOnline > 0'>{{ membersOnline }} online</small>
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
                    :user='user'
                    :messages='0'>
                </messages-team-member>
            </div>

            <input type="text" v-model='message' @keyup.enter='sendMessage' class='messages-list-input'>

            <message-list :messages='messages'></message-list>
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
                membersOnline: 0,
            }
        },

        ready() {
            this.fetchTeamMembers();

            this.fetchMessages().then(this.configureChannel.bind(this));
        },

        methods: {
            configureChannel(response) {
                this.channel = pusher.subscribe(response.channel);

                this.channel.bind('new-message', data => {
                    console.log(data.message);
                    this.messages.push(data.message);
                });

                this.channel.bind('pusher:member_added', this.updateOnlineCount.bind(this));
                this.channel.bind('pusher:member_removed', this.updateOnlineCount.bind(this));
            },

            updateOnlineCount(member) {
                this.membersOnline = this.channel.members.count;
            },

            closeModal() {
                $(this.$el).removeClass('in');
            },

            fetchMessages() {
                return $.get('/api/messages');
            },

            fetchTeamMembers() {
                $.get('/api/account/members').then(response => {
                    this.users = response.filter(user => user.id !== User.id);
                })
            },

            sendMessage() {
                // console.log(this.message);
                $.post('/api/messages/2', { body: this.message });
                this.message = '';
            }
        }
    }
</script>