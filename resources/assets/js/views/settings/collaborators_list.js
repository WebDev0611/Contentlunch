'use strict';

var collaborators_list = Backbone.View.extend({
    template: _.template(`
        <table class="table table-list">
            <thead>
                <tr>
                    <th>Collaborator</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    `),

    initialize: function initialize() {
        this.listenTo(this.collection, "change", this.render);
        this.listenTo(this.collection, "update", this.render);
    },

    render: function render() {
        this.$el.html(this.template());

        this.collection.models.forEach(collaborator => {
            let model = new CollaboratorModel(collaborator);
            let view = new collaborator_row({ model });
            view.render();

            this.$el.find('tbody').append(view.el);
        });

        return this;
    }
});