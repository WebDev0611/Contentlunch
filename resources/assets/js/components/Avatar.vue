<template>
    <div class="dashboard-tasks-img-wrapper">
        <img :src="profileImage" :title="user.name" class="dashboard-tasks-img">
    </div>
</template>

<script>
    export default {
        name: 'avatar',

        props: [ 'user' ],

        computed: {
            profileImage() {
                return this.user.profile_image || '/images/cl-avatar2.png';
            },
        }
    }
</script>