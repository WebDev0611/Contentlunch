'use strict';

// personas.js
(function() {

    var Persona = Backbone.Model.extend({});

    var PersonaCollection = Backbone.Collection.extend({
        model: Persona
    });

    var personas = new PersonaCollection();

    personas.on('add', function(model) {
        var persona = new PersonaRowView({ model: model });
        persona.render();

        $('#personasTable tbody').append(persona.el);
    });

    var PersonaRowView = Backbone.View.extend({
        template: _.template($('#personaRowTemplate').html()),
        tagName: 'tr',
        render: function() {
            this.$el.html(this.template(this.model.toJSON()));

            return this;
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

    new PersonasView({ el: '#personas-view' });

    populateTable();

    function populateTable() {
        getPersonas().then(function(response) {
            var models = response.data.map(function(persona) {
                return new Persona(persona);
            })

            personas.remove(personas.models);
            personas.add(models);
        });
    }

    function getPersonas() {
        return $.ajax({
            method: 'get',
            url: 'personas'
        });
    }

})();