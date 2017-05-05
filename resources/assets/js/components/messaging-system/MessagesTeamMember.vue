<template>
    <div class="messages-list-team-member" @click='selectUser'>
        <div class="messages-list-cell">
            <div class="dashboard-tasks-img-wrapper">
                <img :src="user.profile_image" alt="#" class="dashboard-tasks-img">
            </div>
        </div>
        <div class="messages-list-cell">
            <p class="dashboard-ideas-text">
                {{ user.name }}
                <span class="message-team-member-online" v-show='online'></span>
            </p>
            <small>
                {{ user.email }}
            </small>
        </div>
        <div class="messages-list-cell">
            <span class="badge pull-right" v-show='unreadMessagesCount > 0'>
                {{ unreadMessagesCount }}
            </span>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'messages-team-member',

        data() {
            return {
                User,
            };
        },

        props: [ 'user', 'online' ],

        methods: {
            selectUser() {
                this.markAllMessagesAsRead();
                this.$emit('messages:user-selected', this.user);
            },

            markAllMessagesAsRead() {
                this.user.messages.forEach(message => {
                    message.read = true;
                });
            },
        },

        computed: {
            unreadMessagesCount() {
                return this.user.messages
                    .filter(message => {
                        return !message.read
                             && message.sender_id !== User.id
                             && message.recipient_id == User.id;
                    })
                    .length;
            },
        },
    }
</script>