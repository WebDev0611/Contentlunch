<template>
    <button class="search-bar-button" @click="openChatBar">
        <i class="icon-chat"></i>
    </button>
</template>

<script>
    export default {
        name: 'open-message-bar-button',

        methods: {
            openChatBar() {
                $('#message-modal').addClass('in');
            }
        }
    }
</script>