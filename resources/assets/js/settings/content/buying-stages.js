'use strict';

// buying-stages.js
(function() {

    var BuyingStage = Backbone.Model.extend({});

    var BuyingStageCollection = Backbone.Collection.extend({
        model: BuyingStage
    });

    var buyingStageCollection = new BuyingStageCollection();

    var BuyingStageRowView = Backbone.View.extend({
        events: {
            'click .delete': 'delete'
        },

        template: _.template($('#buyingStageRowTemplate').html()),

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
                url: '/settings/buying_stages/' + this.model.id,
                headers: getHeaders()
            });
        }
    });

    var BuyingModalView = Backbone.View.extend({
        events: {
            "click .sidemodal-close": "dismiss",
            "click #submit-buying-stage": "submitAndShowFeedback"
        },

        initialize: function() {
            this.render();
        },

        showModal() {
            $('#modal-new-buying-stage').modal({ backdrop: 'static' });
        },

        render: function() {
            return this;
        },

        dismiss: function() {
            this.clearForm();
            $('#modal-new-buying-stage').modal('hide');
        },

        clearForm() {
            this.$el.find('#buying-stage-name').val('');
            this.$el.find('#buying-stage-description').val('');
        },

        payload: function() {
            return JSON.stringify({
                name: this.$el.find('#buying-stage-name').val(),
                description: this.$el.find('#buying-stage-description').val(),
            });
        },

        submitAndShowFeedback: function() {
            this.submit()
                .then(this.createBuyingStageAndDismiss.bind(this));
        },

        createBuyingStageAndDismiss: function(response) {
            var buyingStage = new BuyingStage({
                id: response.data.id,
                name: response.data.name,
                description: response.data.description
            });

            buyingStageCollection.add(buyingStage);
            this.dismiss();
        },

        submit: function() {
            return $.ajax({
                type: 'post',
                url: '/settings/buying_stages',
                headers: getHeaders(),
                data: this.payload(),
                processData: false,
                contentType: false
            });
        }
    });

    var Modal = new BuyingModalView({ el: '#modal-new-buying-stage' });

    var BuyingStagesView = Backbone.View.extend({
        events: {
            "click #new-buying-stage": 'openModal'
        },

        initialize: function () {
            this.listenTo(this.collection, 'add', this.addToCollection)
            this.getBuyingStages().then(this.populateTable.bind(this));
        },

        addToCollection: function(model) {
            var buyingStage = new BuyingStageRowView({ model: model });
            buyingStage.render();

            $('#buyingStagesTable tbody').append(buyingStage.el);
        },

        populateTable: function(response) {
            var models = response.data.map(function(buyingStage) {
                return new BuyingStage(buyingStage);
            })

            this.collection.remove(this.collection.models);
            this.collection.add(models);
        },

        getBuyingStages: function() {
            return $.ajax({
                method: 'get',
                url: 'buying_stages'
            });
        },

        openModal: function() {
            Modal.showModal();
        }
    });



    new BuyingStagesView({ el: '#buying-stages-view', collection: buyingStageCollection });

    function getHeaders() {
        return {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': $('input[name=_token]').val()
        };
    }

})();