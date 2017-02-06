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
        fetchCampaignCollaborators(campaign.id);
    }

    function fetchCampaignCollaborators(campaignId) {
        return $.ajax({
            url: `/api/campaigns/${campaignId}/collaborators`,
            headers: getJsonHeader(),
        })
        .then(response => collaborators.reset(response));
    }

})();