<template>
    <div class="plan-ideas-dropdown idea-hover" :class="{ 'hidden': !hover }">
        <button type="button" class="button button-action pull-right" data-toggle="dropdown">
            <i class="icon-add-circle"></i>
        </button>
        <ul class="dropdown-menu dropdown-menu-right">
            <li v-show='write'>
                <a :href="writeLink">Write It</a>
            </li>

            <li v-show='edit'>
                <a :href="editLink">Edit It</a>
            </li>

            <li v-show="park && idea.status !== 'parked'">
                <a @click.prevent='deactivate' href='#'>Park It</a>
            </li>

            <li v-show="park && idea.status === 'parked'">
                <a @click.prevent='activate' href='#'>Unpark It</a>
            </li>

            <li v-show="socialize && idea.status !== 'parked'">
                <a @click.prevent='openCollaboratorModal' href="#">Socialize It</a>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        name: 'ideas-list-item-dropdown',

        props: [ 'idea', 'hover', 'actions' ],

        data() {
            return {
                write: false,
                edit: false,
                park: false,
                socialize: false,
            };
        },

        created() {
            this.actions.split(',').forEach(action => {
                this[action] = true;
            });
        },

        methods: {
            deactivate() {
                this.idea.status = 'parked';

                return $.post(`/idea/${this.idea.id}/park`);
            },

            activate() {
                this.idea.status = 'active';

                return $.post(`/idea/${this.idea.id}/activate`);
            },
        },

        computed: {
            writeLink() {
                return `/idea/${this.idea.id}/write`;
            },

            editLink() {
                return `/idea/${this.idea.id}`;
            },
        },
    }
</script>