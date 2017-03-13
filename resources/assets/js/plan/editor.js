/* the ideas editor JS */
(function(document, $) {
    //view for the editor
    var idea_editor_view = Backbone.View.extend({
        events: {
            "click .save-idea": 'save_idea',
            "click .reject-idea": 'reject_idea',
            "click .park-idea": 'park_idea'
        },

        initialize() {
            console.log(this.model.attributes);
        },

        render() {
            return this;
        },

        save_idea() {
            return $.ajax({
                url: '/idea/update/' + this.model.get('id'),
                data: this.get_form_data(),
                type: 'post',
            })
            .then(function(res) {
                this.showAlert('Successfully saved the idea: ' + res.name);
            }.bind(this));
        },

        get_form_data() {
            return {
                name: $('#idea-name').val(),
                idea: $('#idea-text').val(),
                tags: $('#idea-tags').val(),
            };
        },

        reject_idea() {
            return $.ajax({
                url: '/idea/reject/' + this.model.get('id'),
                type: 'post',
                headers: getCSRFHeader(),
            })
            .then(function(res) {
                this.showAlert('Idea has been rejected!');
            }.bind(this));
        },

        park_idea() {
            return $.post('/idea/' + this.model.get('id') + '/park')
                .then(res => this.showAlert('Idea has been parked!'));
        },

        showAlert(text) {
            var alert_button =  $('<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>');
            var alert_text = $('<div class="alert alert-success alert-forms alert-dismissable" role="alert" />').text(text).append(alert_button);
            $('#responses').append( alert_text );
        }
    });

    var idea = new idea_model(idea_obj);
    var idea_editor = new idea_editor_view({ el: '#idea-editor', model: idea });

})(window.document, jQuery);