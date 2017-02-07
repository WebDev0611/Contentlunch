(function() {

    configureDatePicker();

    function configureDatePicker() {
        let format = 'YYYY-MM-DD';

        $('#start-date').datetimepicker({ format });
        $('#end-date').datetimepicker({ format });
    }

    let collaborators = new CampaignCollaboratorsCollection();

    collaborators.on('add', collaborator => {
        let model = new CollaboratorModel(collaborator);
        let view = new CampaignCollaboratorView({ model });
        $('.campaign-collaborators').append(view.render().el);
    });

    if (campaign && campaign.id) {
        collaborators.populateList(campaign.id).then(response => {
            if (response.data.length) {
                $('#collaborators-sidebar-alert').slideUp('fast');
            }
        });
    }

    $('#campaign-add-person').click(event => {
        event.preventDefault();
        let modal = new AddCampaignCollaboratorModal({
            campaignId: campaign ? campaign.id : null,
            collection: collaborators,
        });

        $('body').prepend(modal.render().el);
        modal.showModal();
    });

})();