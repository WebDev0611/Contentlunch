'use strict';

var CampaignCollaboratorsCollection = Backbone.Collection.extend({
    model: CollaboratorModel,
    url: '/api/account/members',

    populateList(campaignId) {
        return this.fetchData(campaignId).then(response => {
            this.remove(this.models);
            this.add(response.data.map(this.createCollaboratorModel));

            return response;
        });
    },

    fetchData(campaignId) {
        return $.ajax({
            method: 'get',
            url: `/api/campaigns/${campaignId}/collaborators`,
            headers: getJsonHeader(),
        });
    },

    createCollaboratorModel(collaborator) {
        return new CollaboratorModel({
            name: collaborator.name,
            profile_image: collaborator.profile_image || '/images/cl-avatar2.png',
            email: collaborator.email,
        });
    },
});