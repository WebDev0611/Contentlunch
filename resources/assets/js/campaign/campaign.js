(function() {

    configureDatePicker();

    function configureDatePicker() {
        let format = 'YYYY-MM-DD';

        $('#start-date').datetimepicker({ format });
        $('#end-date').datetimepicker({ format });
    }

    let collaborators = new team_members_collection();

    collaborators.on('add', collaborator => {
        let model = new CollaboratorModel(collaborator);
        let view = new CampaignCollaboratorView({ model });
        $('.campaign-collaborator').append(view.render().el);
    });

    if (campaign && campaign.id) {
        fetchCampaignCollaborators(campaign.id)
            .then(populateCollaboratorsList);
    }

    function fetchCampaignCollaborators(campaignId) {
        return $.ajax({
            url: `/api/campaigns/${campaignId}/collaborators`,
            headers: getJsonHeader(),
        });
    }

    function populateCollaboratorsList(response) {
        if (response.data.length) {
            $('#collaborators-sidebar-alert').slideUp('fast');
            collaborators.remove(collaborators.models);
            collaborators.add(response.data);
        }
    }

})();