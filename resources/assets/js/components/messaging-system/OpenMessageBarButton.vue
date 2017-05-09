<template>
    <button class="search-bar-button relative" @click="openChatBar">
        <i class="icon-chat"></i>
        <span class="badge badge-button" v-show='unread'>
            {{ unread }}
        </span>
    </button>
</template>

<script>
    import { mapState } from 'vuex';

    export default {
        name: 'open-message-bar-button',

        methods: {
            openChatBar() {
                $('#message-modal').addClass('in');
            }
        },

        computed: mapState({
            unread: state => state.unreadMessages,
        }),
    }
</script>