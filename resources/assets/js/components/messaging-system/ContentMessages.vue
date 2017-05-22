<template>
    <div>
        <input type="text" v-model='message' @keyup.enter='sendMessage' class='messages-list-input'>

        <ul class="messages-list">
            <li v-for='message in messages' :class="classes(message)">

                <div class="message-author">
                    <img :src="message.senderData.profile_image" alt="">
                </div>
                <div class="message-body">
                    {{ message.body }}
                </div>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        name: 'content-messages',

        props: [ 'contentId' ],

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
                return $.get(`/api/contents/${this.contentId}/messages`).then(response => {
                    this.messages = response.data;

                    return response;
                });
            },

            configureChannel(response) {
               this.channel = pusher.subscribe(response.channel);

               this.channel.bind('new-message', this.addMessageToConversation.bind(this));

               return response;
            },

            addMessageToConversation(data) {
                this.messages.push(data.message);
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