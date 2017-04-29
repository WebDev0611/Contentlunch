<template>
    <div class="sidemodal" id='message-modal'>
        <div class="sidemodal-header">
            <div class="row">
                <div class="col-md-6">
                    <h4 class="sidemodal-header-title">Team Communication</h4>
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

            <div class="messages-list">
                <textarea cols="30"
                          rows="1"
                          class="messages-list-input"
                          v-model='message'
                          @keyup.enter='sendMessage'>
                </textarea>
            </div>
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
                users: [],
                messages: [],
                message: '',
                channel: null,
            }
        },

        ready() {
            this.fetchTeamMembers();

            this.channel = pusher.subscribe('messages');

            this.channel.bind('new-message', data => {
                console.log(data);
            });

            $('textarea.messages-list-input').each(function() {
                let offset = this.offsetHeight - this.clientHeight;

                let resizeTextarea = function(el) {
                    $(el).css('height', 'auto').css('height', el.scrollHeight + offset);
                };

                $(this).on('keyup', function(e) { resizeTextarea(this); });
            });
        },

        methods: {
            closeModal() {
                $(this.$el).removeClass('in');
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