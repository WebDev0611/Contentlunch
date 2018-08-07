(function() {

    //
    // Attachments configuration
    //

    let attachmentUploader = new Dropzone('#attachment-uploader', {
        headers: getCSRFHeader(),
        url: '/edit/attachments',
    });

    attachmentUploader.on('success', function(file, response) {
        var hiddenField = $('<input/>', {
            name: 'attachments[]',
            type: 'hidden',
            value: response.file
        });

        hiddenField.appendTo($('form'));
    });


    //
    // Datepickers configuration
    //

    configureDatePicker();

    function configureDatePicker() {
        let format = 'MM/DD/YYYY';

        $('#start-date').datetimepicker({ format });
        $('#end-date').datetimepicker({ format });
    }

    //
    // Campaign tasks setup
    //

    $('#add-task-button').click(function() {
        add_task(addTaskCallback);
    });

    function addTaskCallback(task) {
        tasks.add(task);
        $('#addTaskModal').modal('hide');

        if (!campaign.id) {
            addTaskToDom(task);
        }
    }

    function addTaskToDom(task) {
        let element = $('[name=tasks]');
        let tasks = element.val().split(',').filter(taskId => taskId !== "");

        tasks.push(task.id);
        element.val(tasks.join(','));
    }

    let tasks = new CampaignTasksCollection();

    tasks.on('add', model => {
        let view = new ContentTaskView({ model });

        $('#campaign-tasks-list').append(view.el);
    });

    if (campaign && campaign.id) {
        let openTasksOnly = false;
        tasks.populateList(campaign.id, openTasksOnly);
    }

    //
    // Collaborators
    //

    let collaborators = new CampaignCollaboratorsCollection();

    collaborators.on('add', collaborator => {
        appendCollaborator(collaborator);
        hideCollaboratorsAlert();
    });

    function appendCollaborator(collaborator) {
        let model = new CollaboratorModel(collaborator);
        let view = new CampaignCollaboratorView({ model });
        $('.campaign-collaborators').append(view.render().el);
    }

    function hideCollaboratorsAlert() {
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