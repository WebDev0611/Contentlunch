'use strict';

// personas.js
(function() {

    var PersonasModalView = Backbone.View.extend({
        events: {
            "click .sidemodal-close": "dismiss",
            "click #submit-persona": "submitAndShowFeedback"
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
        },

        payload: function() {
            return JSON.stringify({
                name: this.$el.find('#persona-name').val(),
                description: this.$el.find('#persona-description').val(),
            });
        },

        headers: function() {
            return {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': this.$el.find('input[name=_token]').val()
            }
        },

        submitAndShowFeedback: function() {
            this.submit();
        },

        submit: function() {
            return $.ajax({
                type: 'post',
                url: '/settings/personas',
                headers: this.headers(),
                data: this.payload(),
                processData: false,
                contentType: false
            });
        }
    });

    var PersonasView = Backbone.View.extend({
        events: {
            "click #new-persona": 'openModal'
        },

        openModal: function() {
            new PersonasModalView({ el: '#modal-new-persona' });
        }
    });

    new PersonasView({ el: '#personas-view' });

})();