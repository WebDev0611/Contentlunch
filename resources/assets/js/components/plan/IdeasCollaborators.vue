<template>
    <div class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="modal-close close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">INVITE COLLABORATORS</h4>
                </div>
                <div class="modal-body">
                    <div class="row" v-show='loaded && !possibleCollaborators.length'>
                        <div class="col-md-8 col-md-offset-2">
                            <div class="empty-collaborators-message text-center">
                                <p>
                                    We couldn't find any other account members. Please use
                                    the field below to invite friends.
                                </p>
                                <invite-form @invited='closeModal'></invite-form>
                            </div>
                        </div>
                    </div>

                    <div class="row" v-show='loaded && possibleCollaborators.length'>
                        <div class="col-md-8 col-md-offset-2">
                            <p class="text-gray text-center">
                                Select the users you want to collaborate with.
                            </p>
                            <loading v-show='!loaded'></loading>
                            <div class="collaborators-list">
                                <ideas-collaborators-list-item
                                    v-for='collaborator in possibleCollaborators'
                                    :collaborator='collaborator'
                                    :key='collaborator.id'>
                                </ideas-collaborators-list-item>
                            </div>

                            <button @click='saveCollaborators' class="button button-primary text-uppercase button-extend">
                                Invite Users
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import bus from '../bus.js';
    import IdeasCollaboratorsListItem from './IdeasCollaboratorsListItem.vue';
    import InviteForm from '../InviteForm.vue';

    export default {
        name: 'ideas-collaborators',

        components: {
            IdeasCollaboratorsListItem,
            InviteForm,
        },

        data() {
            return {
                loaded: false,
                idea: null,
                collaborators: [],
            };
        },

        created() {
            bus.$on('socialize', idea => {
                this.idea = idea;
                this.loadIdeaCollaborators();
                this.showModal();
            });
        },

        methods: {
            loadIdeaCollaborators() {
                this.loaded = false;
                this.collaborators = [];

                return $.get(this.url()).then(response => {
                    this.collaborators = response.data;
                    this.loaded = true;
                });
            },

            url() {
                let params = $.param({ possible_collaborators: 1 });

                return `/api/ideas/${this.idea.id}/collaborators?${params}`;
            },

            showModal() {
                $(this.$el).modal('show');
            },

            closeModal() {
                $(this.$el).modal('hide');
            },

            collaboratorIds() {
                return this.collaborators
                    .filter(user => user.is_collaborator)
                    .map(user => user.id);
            },

            saveCollaborators() {
                let collaborators = this.collaboratorIds();

                $.post(`/api/ideas/${this.idea.id}/collaborators`, { collaborators })
                    .then(response => {
                        this.showSuccessFeedback();
                        this.closeModal();
                    });
            },

            showSuccessFeedback() {
                swal('Idea Shared!', 'The selected users were invited to collaborate on this idea.', 'success');
            },
        },

        computed: {
            possibleCollaborators() {
                return this.collaborators.filter(user => !user.is_guest);
            }
        }
    }
</script>