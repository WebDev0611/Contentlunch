'use strict';

(function() {

    fetchCollaborators().then(appendCollaborators);

    function fetchCollaborators() {
        let collaborators = new collaborators_collection();

        return collaborators.fetch().then(function(response) {
            let view = new collaborators_list();

            $('.collaborators-list-container').html(view.render().$el.html());

            return collaborators;
        });
    }

    function appendCollaborators(collaborators) {
        collaborators.models.forEach(collaborator => {
            let model = new CollaboratorModel(collaborator);
            let view = new collaborator_row({ model });

            $('.collaborators-list-container tbody').append(view.render().$el);
        });
    }

})();