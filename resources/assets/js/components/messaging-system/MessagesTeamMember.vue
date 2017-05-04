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
    </div>
</template>

<script>
    export default {
        name: 'messages-team-member',

        props: [ 'user', 'online' ],

        methods: {
            selectUser() {
                this.$emit('messages:user-selected', this.user);
            }
        }
    }
</script>