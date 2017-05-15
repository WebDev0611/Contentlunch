<template>
    <div class="sidepanel-body">
        <div class="pane-users">
            <ul class="list-unstyled list-users">
                <li v-for='guest in guests'>
                    <a>
                        <div class="user-avatar">
                            <img :src="guest.profile_image" alt="#">
                        </div>
                        <p class="title">{{ guest.name }}</p>
                        <p class="email">{{ guest.email }}</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'guests-list',

        props: ['contentId', 'type'],

        data() {
            return {
                guests: [],
            };
        },

        created() {
            this.fetchGuests();
        },

        methods: {
            fetchUrl() {
                switch(this.type) {
                    case 'content': return `/api/contents/${this.contentId}/guests`;
                    case 'campaign': //
                    case 'idea': //

                    default: return null;
                }
            },

            fetchGuests() {
                $.get(this.fetchUrl()).then(response => this.guests = response.data);
            }
        }
    }
</script>