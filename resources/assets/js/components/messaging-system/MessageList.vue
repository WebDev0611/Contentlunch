<template>
    <ul class="messages-list">
        <li v-for='message in displayedMessages' :class="classes(message)">

            <div class="message-author">
                <img :src="message.senderData.profile_image" alt="">
            </div>
            <div class="message-body">
                {{ message.body }}
            </div>
        </li>
    </ul>
</template>

<script>
    export default {
        name: 'message-list',

        props: ['messages', 'selected'],

        methods: {
            classes(message) {
                return {
                    sent: message.sender_id == this.$store.state.user.id,
                    received: message.sender_id != this.$store.state.user.id
                };
            },
        },

        computed: {
            displayedMessages() {
                return this.selected
                    ? _(this.messages).find({ id: this.selected.id }).messages
                    : [];
            }
        },
    }
</script>