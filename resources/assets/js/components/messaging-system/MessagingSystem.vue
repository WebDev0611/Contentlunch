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

            <ul class="messages-list">
                <li v-for='message in messages'
                    :class="{ sent: message.sender_id == User.id, received: message.recipient_id == User.id }">

                    {{ message.body }}
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    import MessagesTeamMember from './MessagesTeamMember.vue';

    export default {
        name: 'messaging-system',
        components: {
            MessagesTeamMember,
        },

        data() {
            return {
                User,
                users: [],
                messages: [],
                message: '',
                channel: null,
                membersOnline: 0,
            }
        },

        ready() {
            this.fetchTeamMembers();

            this.fetchMessages().then(this.configureChannel);
        },

        methods: {
            configureChannel(response) {
                this.channel = pusher.subscribe(response.channel);

                this.channel.bind('new-message', data => {
                    this.messages.push(data.message);
                });
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