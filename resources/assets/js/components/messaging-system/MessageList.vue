<template>
    <ul class="messages-list">
        <li v-for='message in messages'
            :class="{ sent: message.sender_id == User.id, received: message.recipient_id != User.id }">

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

        props: ['messages'],

        data() {
            return {
                User,
            }
        }
    }
</script>