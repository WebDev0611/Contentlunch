<template>
    <div class='team-communication'>
        <input type="text"
            v-model='message'
            v-show='hasAccess'
            @keyup.enter='sendMessage'
            class='messages-list-input'>

        <ul class="messages-list" v-show='hasAccess'>
            <li v-for='message in messages' :class="classes(message)">

                <div class="message-author">
                    <img :src="message.senderData.profile_image" :title="message.senderData.name">
                </div>
                <div class="message-body">
                    {{ message.body }}
                </div>
            </li>
        </ul>

        <div class="alert alert-info alert-forms" v-show='!hasAccess'>
            You must be an active collaborator or invited guest to access team communication.
        </div>
    </div>
</template>

<script>
    export default {
        name: 'content-messages',

        props: [ 'contentId', 'hasAccess' ],

        data() {
            return {
                message: '',
                messages: [],
                channel: null,
            }
        },

        created() {
            this.fetchMessages();
        },

        methods: {
            fetchMessages() {
                return $.get(`/api/contents/${this.contentId}/messages`)
                    .then(this.configureMessages.bind(this))
                    .then(this.configureChannel.bind(this));
            },

            configureMessages(response) {
                this.messages = response.data;

                return response;
            },

            configureChannel(response) {
                let pusher = this.configurePusher();

                this.channel = pusher.subscribe(response.channel);
                this.channel.bind('new-message', this.addMessageToConversation.bind(this));

                return response;
            },

            configurePusher() {
                return new Pusher(pusherKey, {
                    authEndpoint: `/api/contents/${this.contentId}/messages/auth`,
                    auth: {
                        headers: {
                            'X-CSRF-TOKEN': $('input[name=_token]').val(),
                        },
                    },
                });
            },

            addMessageToConversation(data) {
                this.messages.unshift(data.message);
            },

            classes(message) {
                return {
                    sent: message.sender_id == this.$store.state.user.id,
                    received: message.sender_id != this.$store.state.user.id
                };
            },

            sendMessage() {
                let url = `/api/contents/${this.contentId}/messages`;
                $.post(url, { body: this.message });
                this.message = '';
            },
        },
    }
</script>