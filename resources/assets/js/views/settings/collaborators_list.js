'use strict';

var collaborators_list = Backbone.View.extend({
    template: _.template(`
        <table class="table table-list">
            <thead>
                <tr>
                    <th></th>
                    <th>Collaborator</th>
                    <th colspan=2></th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    `),

    initialize() {
        this.listenTo(this.collection, "change", this.render);
        this.listenTo(this.collection, "update", this.render);
    },

    render() {
        this.$el.html(this.template());

        this.collection.models.forEach(collaborator => {
            let model = new CollaboratorModel(collaborator);
            let view = new collaborator_row({ model });

            this.$('tbody').append(view.render().$el);
            view.delegateEvents();
        });

        return this;
    },
});