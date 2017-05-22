<template>
    <div class="messages-list-header">
        <button @click='closeConversation'>
            <i class="fa fa-chevron-left"></i>
        </button>
        <p v-if='selected'>
            <span class="message-author">
                <img :src="selected.profile_image" alt="">
            </span>
            {{ selected.name }}
        </p>
    </div>
</template>

<script>
    export default {
        name: 'message-list-header',

        props: ['selected'],

        methods: {
            closeConversation() {
                this.$emit('messages:close-conversation');
            }
        }
    }
</script>