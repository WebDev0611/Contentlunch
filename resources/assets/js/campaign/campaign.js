(function() {

    //
    // Campaign tasks setup
    //

    $('#add-task-button').click(function() {
        add_task(addTaskCallback);
    });

    function addTaskCallback(task) {
        // tasks.add(new task_model(task));
        $('#addTaskModal').modal('hide');
    }

    //
    // Datepickers configuration
    //

    configureDatePicker();

    function configureDatePicker() {
        let format = 'YYYY-MM-DD';

        $('#start-date').datetimepicker({ format });
        $('#end-date').datetimepicker({ format });
    }

    //
    // Collaborators
    //

    let collaborators = new CampaignCollaboratorsCollection();

    collaborators.on('add', collaborator => {
        appendCollaborator(collaborator);
        hideAlert();
    });

    function appendCollaborator(collaborator) {
        let model = new CollaboratorModel(collaborator);
        let view = new CampaignCollaboratorView({ model });
        $('.campaign-collaborators').append(view.render().el);
    }

    function hideAlert() {
        let $alert = $('#collaborators-sidebar-alert');
        if ($alert.is(':visible')) {
            $alert.slideUp('fast');
        }
    }

    if (campaign && campaign.id) {
        collaborators.populateList(campaign.id);
    }

    $('#campaign-add-person').click(event => {
        event.preventDefault();
        let modal = new AddCampaignCollaboratorModal({
            campaignId: campaign ? campaign.id : null,
            collection: collaborators,
            form: $('#campaign_editor'),
            parentList: $('.campaign-collaborators'),
        });

        $('body').prepend(modal.render().el);
        modal.showModal();
    });

})();