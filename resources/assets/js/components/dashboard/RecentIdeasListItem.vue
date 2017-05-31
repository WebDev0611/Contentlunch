<template>
    <div v-on:mouseenter='hover = true' v-on:mouseleave='hover = false'>
        <div class="dashboard-ideas-cell cell-size-5">
            <div class="dashboard-tasks-img-wrapper">
                <img :src="profileImage" alt="#" class="dashboard-tasks-img">
            </div>
        </div>
        <div class="dashboard-ideas-cell cell-size-80">
            <p class="dashboard-ideas-text">{{ idea.name }}</p>
            <span class="dashboard-ideas-text small">{{ createdAt }}</span>
        </div>
        <div class="dashboard-ideas-cell cell-size-15">
            <div class="dashboard-ideas-dropdown idea-hover" :class="{ 'hidden': !hover }">
                <button type="button" class="button button-action" data-toggle="dropdown">
                    <i class="icon-add-circle"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-right">
                    <li>
                        <a :href="writeLink">Write It</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'recent-ideas-list-item',

        props: [ 'idea' ],

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