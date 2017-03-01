'use strict';

// personas.js
(function() {

    var Persona = Backbone.Model.extend({});

    var PersonaCollection = Backbone.Collection.extend({
        model: Persona
    });

    var personaCollection = new PersonaCollection();

    var PersonaRowView = Backbone.View.extend({
        events: {
            'click .delete': 'delete'
        },

        template: _.template($('#personaRowTemplate').html()),

        tagName: 'tr',

        render: function() {
            this.$el.html(this.template(this.model.toJSON()));

            return this;
        },

        delete: function(event) {
            event.preventDefault();
            event.stopPropagation();

            this.sendDeleteRequest();
            this.remove();
        },

        sendDeleteRequest: function() {
            return $.ajax({
                method: 'delete',
                url: '/settings/personas/' + this.model.id,
                headers: getJsonHeader()
            });
        }
    });

    var PersonasModalView = Backbone.View.extend({
        events: {
            "click .sidemodal-close": "dismiss",
            "click #submit-persona": "submitAndShowFeedback"
        },

        initialize() {
            this.render();
        },

        render() {
            return this;
        },

        showModal() {
            $('#modal-new-persona').modal('show');
        },

        dismiss() {
            $('#modal-new-persona').modal('hide');
        },

        payload() {
            return JSON.stringify({
                name: this.$el.find('#persona-name').val(),
                description: this.$el.find('#persona-description').val(),
            });
        },

        submitAndShowFeedback() {
            this.submit()
                .then(this.createPersonaAndDismiss.bind(this));
        },

        createPersonaAndDismiss(response) {
            var persona = new Persona({
                id: response.data.id,
                name: response.data.name,
                description: response.data.description
            });

            personaCollection.add(persona);
            this.dismiss();
        },

        submit() {
            return $.ajax({
                type: 'post',
                url: '/settings/personas',
                headers: getJsonHeader(),
                data: this.payload(),
                processData: false,
                contentType: false
            });
        }
    });

    var Modal = new PersonasModalView({ el: '#modal-new-persona' });

    var PersonasView = Backbone.View.extend({
        events: {
            "click #new-persona": 'openModal'
        },

        initialize() {
            this.listenTo(this.collection, 'add', this.addToCollection)
            this.getPersonas().then(this.populateTable.bind(this));
        },

        addToCollection(model) {
            var persona = new PersonaRowView({ model: model });
            persona.render();

            $('#personasTable tbody').append(persona.el);
        },

        populateTable(response) {
            var models = response.data.map(function(persona) {
                return new Persona(persona);
            })

            this.collection.remove(this.collection.models);
            this.collection.add(models);
        },

        getPersonas() {
            return $.ajax({
                method: 'get',
                url: 'personas'
            });
        },

        openModal() {
            Modal.showModal();
        }
    });



    new PersonasView({ el: '#personas-view', collection: personaCollection });

})();