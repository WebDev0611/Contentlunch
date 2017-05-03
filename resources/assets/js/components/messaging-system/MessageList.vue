<template>
    <div>
        <div class="messages-list-header">
            <button>
                <i class="fa fa-chevron-left"></i>
            </button>
            <p v-if='selected'>
                <span class="message-author">
                    <img :src="selected.profile_image" alt="">
                </span>
                {{ selected.name }}
            </p>
        </div>
        <ul class="messages-list">
            <li v-for='message in displayedMessages'
                :class="{ sent: message.sender_id == User.id, received: message.sender_id != User.id }">

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
        name: 'message-list',

        props: ['messages', 'selected'],

        data() {
            return {
                User,
            };
        },

        computed: {
            displayedMessages() {
                return this.selected
                    ? _(this.messages).find({ id: this.selected.id }).messages
                    : [];
            }
        },

        data() {
            return {
                User,
            }
        }
    }
</script>