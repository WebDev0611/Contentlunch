'use strict';

(function() {

    let collaborators = new collaborators_collection();

    collaborators.fetch().then(function(response) {
        let collaboratorsView = new collaborators_list({
            collection: collaborators,
        });

        collaboratorsView.render();
        $('.collaborators-list-container').html(collaboratorsView.$el.html());
    });

})();