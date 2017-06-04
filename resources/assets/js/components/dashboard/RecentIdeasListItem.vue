<template>
    <div v-on:mouseenter='hover = true' v-on:mouseleave='hover = false'>
        <div class="dashboard-ideas-cell cell-size-5">
            <avatar :user='idea.user'></avatar>
        </div>
        <div class="dashboard-ideas-cell cell-size-80">
            <p class="dashboard-ideas-text">{{ idea.name }}</p>
            <span class="dashboard-ideas-text small">{{ createdAt }}</span>
        </div>
        <div class="dashboard-ideas-cell cell-size-15">
            <ideas-list-item-dropdown
                :idea='idea'
                :hover='hover'
                actions='write'>
            </ideas-list-item-dropdown>
        </div>
    </div>
</template>

<script>
    import IdeasListItemDropdown from '../plan/IdeasListItemDropdown.vue';
    import Avatar from '../Avatar.vue';

    export default {
        name: 'recent-ideas-list-item',

        props: [ 'idea' ],

        components: { Avatar, IdeasListItemDropdown },

        data() {
            return {
                hover: false,
            };
        },

        computed: {
            profileImage() {
                return this.idea.user.profile_image || '/images/cl-avatar2.png';
            },

            createdAt() {
                return moment.utc(this.idea.created_at).local().format('MM/DD/YYYY h:mma');
            },

            writeLink() {
                return `/idea/${this.idea.id}/write`;
            }
        },
    }
</script>