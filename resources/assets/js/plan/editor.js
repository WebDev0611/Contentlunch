/* the ideas editor JS */
(function(document, $) {
    //view for the editor
    var idea_editor_view = Backbone.View.extend({
        events: {
            "click .save-idea": 'saveIdea',
            "click .reject-idea": 'rejectIdea',
            "click .park-idea": 'parkIdea'
        },

        initialize() {
            console.log(this.model.attributes);
        },

        render() {
            return this;
        },

        saveIdea() {
            return $.ajax({
                url: '/idea/' + this.model.get('id') + '/update',
                data: this.formData(),
                type: 'post',
            })
            .then(res => this.showAlert('Successfully saved the idea: ' + res.name));
        },

        formData() {
            return {
                name: $('#idea-name').val(),
                idea: $('#idea-text').val(),
                tags: $('#idea-tags').val(),
            };
        },

        rejectIdea() {
            return $.post('/idea/' + this.model.get('id') + '/reject/')
                .then(res => this.showAlert('Idea has been rejected!'));
        },

        parkIdea() {
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