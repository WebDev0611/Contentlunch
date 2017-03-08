'use strict';

(function($) {

	var topic_result = Backbone.Model.extend({
		defaults:{
			keyword: '',
			volume: 0,
			timestamp: new Date().getTime()
		}
	});

	var result_view = Backbone.View.extend({
		className: "col-md-6",
		tagName: "div",
		events: {
			"click input": "select_content"
		},

		template: _.template(`
            <label for="<%= camelize(keyword) %>" class="checkbox-tag">
                <input id="<%= camelize(keyword) %>" type="checkbox">
                <span><%= keyword %></span>
            </label>
        `),

		initialize() {
			this.listenTo(this.model, "remove", this.removeFromDOM);
		},

		render() {
			this.$el.html(this.template(this.model.attributes));
			return this;
		},

		removeFromDOM() {
			var that = this;
			this.$el.fadeOut(200, () => that.$el.remove());
		},

		select_content() {
			if (!this.model.get('selected')) {
				this.model.set('selected', true);
			} else {
				this.model.set('selected', false);
			}
		}
	});

	var long_tail_collection = Backbone.Collection.extend({
		model: topic_result
	});
	var short_tail_collection = Backbone.Collection.extend({
		model: topic_result
	});

	/* idea JS for the modal */
	var selected_content = Backbone.Collection.extend({
		model: topic_result
	});
	var selected_content_view = Backbone.View.extend({
		template: _.template($('#selected-topic-template').html()),

		initialize() {
			this.render();
		},

		render() {
			this.$el.html(this.template(this.model.attributes));
		}
	});

	var idea_model = Backbone.Model.extend({
		defaults:{
			name: "IDEA 1",
			body: "This is where the idea text goes",
			tags: [],
			collaborators: [],
			content: new selected_content()
		}
	});

	var create_idea_view = Backbone.View.extend({
		events: {
			"click .save-idea": "save",
			"click .park-idea": "park",
            "click #open-collab-modal": "openCollabModal"
		},

        collaborators: [],

		initialize() {
		    this.listenTo(Backbone, 'idea_collaborators:selected', this.saveCollaborators.bind(this));
			this.listenTo(this.model.attributes.content,'update',this.render);
			this.render();
		},

        saveCollaborators(users) {
		    this.collaborators = users;
            this.renderCollaborators();
        },

        renderCollaborators() {
            let $list = this.$el.find('#ideas-collaborator-list');

            $list.html('');
            this.collaborators.forEach(user => {
                user.profile_image = user.profile_image || '/images/cl-avatar2.png';

                let template = _.template(`
                    <li>
                        <img src="<%= profile_image %>" title="<%= name %>" alt="<%= name %>">
                        <p><%= name %></p>
                    </li>
                `);
                let $el = $(template(user));

                $list.append($el);
            });
        },

		render() {
			var view = this;

			view.$el.find('#selected-content').html('');
			view.model.attributes.content.each(function(model){
				var selectedContent = new selected_content_view({ model });
				view.$el.find('#selected-content').append(selectedContent.$el);
			});

			if (view.model.attributes.content.length < 1) {
				view.$el.find('.form-delimiter').hide();
			} else {
				view.$el.find('.form-delimiter').show();
			}
		},

		park() {
			this.store('parked');
		},

		save() {
			this.store('active');
		},

        disableForm() {
            $('.park-idea').prop('disabled',true);
            $('.save-idea').prop('disabled',true);
            $('#idea-status-alert').addClass('hidden');
        },

        formIsValid() {
            return $('.idea-name').val().length > 1;
        },

        formData() {
            return {
                name: $('.idea-name').val(),
                idea: $('.idea-text').val(),
                tags: $('.idea-tags').val(),
                status: status || 'active',
                content: this.model.attributes.content.map(model => model.attributes),
                collaborators: this.collaborators.map(user => user.id),
            };
        },

        store(status) {
            let view = this;

            this.disableForm();

			if (!this.formIsValid()) {
				this.showError('Idea title required');
				return;
			}

			var loadingIMG = $('<img src="/images/loading.gif" style="max-height:30px;" />');
			$('#idea-menu').prepend(loadingIMG);

			return $.ajax({
			    url: '/ideas',
			    type: 'post',
			    data: this.formData(),
			    dataType: 'json',
			})
			.then(function (data) {
				$(loadingIMG).remove();
				view.hideModal();
				view.clearForm();
				view.render();
			})
			.catch(response => {
				$(loadingIMG).remove();
				view.hideModal();
				view.clearForm();

                if (response.status === 403) {
                    showUpgradeAlert(response.responseJSON.data);
                } else {
                    swal('Error!', response.responseJSON.data, 'error');
                }
			});
		},

		hideModal() {
			this.$el.modal('hide');
		},

		showError(msg) {
			$('#idea-status-alert')
				.toggleClass('hidden')
				.toggleClass('alert-danger')
				.show();

			$('#idea-status-text').text(msg);
			$('.park-idea').prop('disabled',false);
			$('.save-idea').prop('disabled',false);
		},

		clearForm() {
		    this.deselectModels();

			$('.idea-name').val('');
			$('.idea-text').val('');
			$('.idea-tags').val('');
			$('.park-idea').prop('disabled',false);
			$('.save-idea').prop('disabled',false);
		},

        deselectModels() {
		    let view = this;
            this.model.attributes.content.each(function(model) {
                if (model) {
                    model.set('selected',false);
                    view.model.attributes.content.remove(model);
                }
            });
        },

        openCollabModal() {
            var collabModal = new AddIdeaCollaboratorModalView();
            collabModal.showModal();
        }
	});

	var new_idea = new idea_model();
	var idea_form = new create_idea_view({ el: '#createIdea', model: new_idea });

	/* main page event setup */
	$(function(){
		var long_tail_results = new long_tail_collection();
		var short_tail_results = new short_tail_collection();

		const sf = r => r.keyword.split(' ').length <= 3;
		const lf = r => r.keyword.split(' ').length >= 2;
		const v_sort = (a, b) => b.volume - a.volume;
		const map_result = model => {
			return {
				keyword: model.keyword,
				volume: model.volume,
			};
		};

		var get_topic_data = function(term = '') {
			var search_obj = {};

			if (term.length > 3) {
				var loadingGif = $('<img src="/images/loading.gif" style="max-height:30px;" />');

				$('span.input-form-button-action').append(loadingGif);
				search_obj.keyword = term;


				$.getJSON('/topics', search_obj)
					.then(res => {
						$('span.input-form-button-action img').remove();

						var lt = res.results.filter(lf).map(map_result).sort(v_sort);
						var st = res.results.filter(sf).map(map_result).sort(v_sort);

						short_tail_results.add(st);
						long_tail_results.add(lt);
					})
					.catch(response => {
						$('span.input-form-button-action img').remove();

						if (response.status === 403) {
						    showUpgradeAlert(response.responseJSON.data);
						} else {
						    swal('Error!', response.responseJSON.data, 'error');
						}
					});
			}
		};

        const topicSearch = () => {
            long_tail_results.remove(long_tail_results.models);
            short_tail_results.remove(short_tail_results.models);
            get_topic_data($('#topic-search-val').val());
        }

        const topicInputKeyUpHandler = event => {
            const keyCode = event.keyCode;
            const ENTER_KEY = 13;
            const value = $('#topic-search-val').val();

            if (keyCode === ENTER_KEY && value) {
                topicSearch();
            }
        }

        $('#topic-search').click(topicSearch);
        $('#topic-search-val').keyup(topicInputKeyUpHandler);

		long_tail_results.on('add',function(m){
			var result = new result_view({model: m});
			result.render();
			result.$el.hide();
			result.$el.fadeIn();
			$('#long-tail-results').append( result.el );
		});
		long_tail_results.on('change', function(l){
			if(l.get('selected')){
				new_idea.attributes.content.add(l);
			}else{
				new_idea.attributes.content.remove(l);
			}
		});


		short_tail_results.on('add',function(m){
			var result = new result_view({model: m});
			result.render();
			result.$el.hide();
			result.$el.fadeIn();
			$('#short-tail-results').append( result.el );
		});
		short_tail_results.on('change', function(l){
			if(l.get('selected')){
				new_idea.attributes.content.add(l);
			}else{
				new_idea.attributes.content.remove(l);
			}
		});

		//get_topic_data();

		//task method
		//runs the action to submit the task
		$('#add-task-button').click(function() {
		    add_task(addTaskCallback);
		});

		function addTaskCallback(task) {
		    $('#addTaskModal').modal('hide');
		}
	});

})(jQuery);