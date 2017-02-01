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

    render() {
        this.$el.html(this.template());

        return this;
    },

});