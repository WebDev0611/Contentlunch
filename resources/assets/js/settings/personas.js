'use strict';

// personas.js
(function() {

    var PersonasModalView = Backbone.View.extend({
        events: {
            "click .sidemodal-close": "dismiss"
        },
        initialize: function() {
            this.render();
        },
        render: function() {
            $('#modal-new-persona').modal('show');

            return this;
        },
        dismiss: function() {
            $('#modal-new-persona').modal('hide');
        }
    });

    var PersonasView = Backbone.View.extend({
        events: {
            "click #new-persona": 'openModal'
        },

        openModal: function() {
            console.log('heuheuheu');
            new PersonasModalView({ el: '#modal-new-persona' });
        }
    });

    new PersonasView({ el: '#personas-view' });

})();