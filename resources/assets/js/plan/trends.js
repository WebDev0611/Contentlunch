let camelize = function(str) {
return str.replace(/(?:^\w|[A-Z]|\b\w|\s+)/g, function(match, index) {
if (+match === 0) return ""; // or if (/\s+/.test(match)) for white spaces
return index == 0 ? match.toLowerCase() : match.toUpperCase();
});
};


(function($){
	let trend_api_host = '/trending';

	let create_message_view = Backbone.View.extend({
		initialize: function(){
			this.listenTo(this.collection,"update",this.render);
			this.listenTo(this.collection,"change",this.render);
		},
		render: function(){
			let selected = this.collection.filter(function(m){
				return m.get('selected');
			});

			if(selected.length > 0 ){
				this.$el.html('Create an idea from ' + selected.length + ' selected items');
			}else{
				this.$el.html('');
			}
		}
	});

	let create_idea_cont_view = Backbone.View.extend({
		events:{
			"click": "unselect"
		},
		template: _.template( $('#selected-trend-template').html() ),
		initialize: function(){
			this.render();
		},
		render: function(){
			this.$el.html(this.template( this.model.attributes ) );
			return this;
		},
		unselect: function(){
			this.model.set('selected',false);
			this.$el.toggleClass('tombstone-active');
		}
	});

    let create_idea_modal = Backbone.View.extend({
        events:{
            "click .save-idea": "save",
            "click .park-idea": "park"
        },
        initialize:function(){
            this.listenTo(this.collection, "update", this.render);
            this.listenTo(this.collection, "change", this.render);
        },
        render:function(){
            let view = this;
            let selected = this.collection.where({selected: true});
            view.$el.find('#selected-content').html('');

            selected.forEach(function(m){
                let sel_cont_view = new create_idea_cont_view({model: m});
                view.$el.find('#selected-content').append( sel_cont_view.el );
            });
            this.$el.find('.sidemodal-header-title').text('Create an idea from ' + selected.length + ' selected items');
            if( selected.length < 1 ){
                this.$el.find('.form-delimiter').hide();
            }else{
                this.$el.find('.form-delimiter').show();
            }
        },
        hide_modal: function(){
            this.$el.modal('hide');
        },
        clear_form: function(){
            $('.idea-name').val('');
            $('.idea-text').val('');
            $('.idea-tags').val('');
            this.collection.each(function(m){
                m.set('selected',false);
            });
            $('.save-idea').prop('disabled',false);
            $('.park-idea').prop('disabled',false);
        },
        save: function(){
            this.store('active');
        },
        park: function(){
            this.store('parked');
        },
        show_error: function(msg){
            $('#idea-status-alert')
                .toggleClass('hidden')
                .toggleClass('alert-danger')
                .show();

            $('#idea-status-text').text(msg);
            $('.save-idea').prop('disabled',false);
            $('.park-idea').prop('disabled',false);
        },
        store: function(action){
            let view = this;

            $('.save-idea').prop('disabled',true);
            $('.park-idea').prop('disabled',true);

            $('#idea-status-alert').addClass('hidden');
            if( $('.idea-name').val().length < 1 ){
                view.show_error('Idea title required');
                return;
            }
            let loadingIMG = $('<img src="/images/loading.gif" style="max-height:30px;" />');
            console.log('loading image here');
            $('#idea-menu').prepend(loadingIMG);
            //saves the form data
            let content = this.collection.where({ selected: true });
            let idea_obj = {
                name: $('.idea-name').val(),
                idea: $('.idea-text').val(),
                tags: $('.idea-tags').val(),
                status: action,
                content: content.map(function(m){
                    return m.attributes;
                })
            };
            $.ajax({
                url: '/ideas',
                type: 'post',
                data: idea_obj,
                headers: {
                    'X-CSRF-TOKEN': $('input[name=_token]').val()
                },
                dataType: 'json',
                success: function (data) {
                    $(loadingIMG).remove();
                    view.hide_modal();
                    view.clear_form();
                }
            });
        }
    });

    let share_trend_modal = Backbone.View.extend({
        events:{
            "click .share-trend": "share",
            "change #connectionType": "connectionTypeUpdate"
        },
        selectedTrend: null,
        selectedConnection: null,
        initialize: function() {
            this.$el.on("show.bs.modal", this.updateTrendSelection.bind(this));
            this.listenTo(this.collection, "update", this.render);
            this.listenTo(this.collection, "change", this.render);
            this.populateConnections();
            this.render();
        },

        render: function() {
            let selected = this.collection.where({selected: true});
            this.$el.find('#selected-content').html('');

            selected.forEach(m => {
                let sel_cont_view = new create_idea_cont_view({model: m});
                this.$el.find('#selected-content').append( sel_cont_view.el );
            });

            if( selected.length < 1 ){
                this.$el.find('.form-delimiter').hide();
            }else{
                this.$el.find('.form-delimiter').show();
            }
        },

        populateConnections: function() {
            let $connectionTypeSelect = $("#connectionType");
            if($connectionTypeSelect.find("option").length === 0){
                $.ajax({
                    url: "/api/connections",
                    success: function(response) {
                        let connections = response.data,
                            $options = $("<div>");

                        $options.append($("<option>", {value: "none", text: "-- Select Connection --", selected: true}));

                        for(let i=0; i<connections.length; i++){
                            $options.append($("<option>", {
                                value: connections[i].id,
                                text: connections[i].name,
                                "data-type": connections[i].content_type }));
                        }

                        $options.append($("<option>", {value: "new", text: "-- Add New Connection --"}));

                        $connectionTypeSelect.append($options.html());
                    }
                });
            }
        },

        reset: function() {
            this.$el.find('#trend-share-alert').hide();
            this.$el.find("#connectionType").val("none");
            this.$el.find('.post-text').val('');
            this.$el.find('.hashtags').val('');
            this.collection.each(function(m){
                m.set('selected',false);
            });
            this.selectedConnection = null;
            this.selectedTrend = null;
        },

        connectionTypeUpdate: function() {
            let $connectionSelect = this.$el.find("#connectionType"),
                $selectedOption = $connectionSelect.find("option:selected");
            this.selectedConnection = {id: $selectedOption.val(), name: $selectedOption.text()};
            this.$el.find(".share-trend").attr("disabled", $selectedOption.val() === "none" || $selectedOption.val() === "new");
            if($selectedOption.data("type") === "Tweet") {
                this.$el.find(".hash-tags").closest(".input-form-group").removeClass("hide");
                this.$el.find(".character-limit-label").removeClass("hide");
                this.$el.find(".post-text").attr("maxLength", 140);
            }else if($selectedOption.val() === "new"){
                window.location.href = "/settings/connections";
            }else{
                this.$el.find(".hash-tags").closest(".input-form-group").addClass("hide");
                this.$el.find(".character-limit-label").addClass("hide");
                this.$el.find(".post-text").removeAttr("maxlength");
            }
        },

        updateTrendSelection: function(event) {
            // Clear the selected status of all trend articles.
            $(".tombstone-active").trigger("click");

            // Then reselect only the one we want to share and grab it's id.
            this.selectedTrend = $(event.relatedTarget).closest(".tombstone").trigger("click").data("trend-id");
        },

        handleSuccess: function(res){
            let $trendShareCompletedModal = $("#trendShareCompleted");
            this.$el.modal('hide');

            $trendShareCompletedModal.find(".article-title").text(res.trend.attributes.title);
            $trendShareCompletedModal.find('.modal-social ').html("")
                .append($('<span />', { class: 'icon-social-' + res.published_connections[0] }))
                .append($('<div />', { class: 'connection-name', text: this.selectedConnection.name  }));
            $trendShareCompletedModal.modal();

            this.reset();
        },

        handleError: function(errors){
            let errorText = "";

            if(typeof errors === "string"){
                errorText = errors;
            }else{
                for(let i=0; i<errors.length; i++){
                    errorText += errors[i][Object.keys(errors[i])[0]];
                    if(errors[i+1]){
                        errorText += "<br />";
                    }
                }
            }

            this.$el.find('#trend-status-text').html(errorText);
            this.$el.find('#trend-share-alert').slideDown();

        },

        loading: function(loading){
            if(loading){
                this.$el.find("#trend-share-loading").show();
                this.$el.find(".share-trend").hide();
            }else{
                this.$el.find("#trend-share-loading").hide();
                this.$el.find(".share-trend").show();
            }
        },

        share: function() {
            let trend = this.collection.find(trend => { return trend.id === this.selectedTrend }),
                data = trend.attributes;

            this.loading(true);

            data.body = $(".post-text").val() + " " + trend.attributes.link;
            data.type = "trend";
            data.connection = this.selectedConnection.id;

            $.ajax({
                headers: getCSRFHeader(),
                method: 'post',
                url: `/api/trends/share/${this.selectedConnection.id}`,
                data: data,
                success: res => {
                    if(!res.errors.length){
                        res.trend = trend;
                        this.handleSuccess(res);
                    }else{
                        this.handleError(res.errors);
                    }
                },
                error: error => {
                    this.handleError("We've run into an error. Please try again later.");
                },
                complete: () => {
                    this.loading(false);
                }
            });
        }
    });
	
	$(function(){

        $('#trend-search').on("click", function(e){
            let search_val = $('#trend-search-input').val() || "";
            get_trending_topics(search_val);
        });

        $('#trend-search-input').on("keyup", function(e){
            if(!!e.keyCode && e.keyCode !== 13){
                return;
            }
            $('#trend-search').click();
        });

		let results = new trend_result_collection();

		let get_trending_topics = function(topic){
			topic = topic || '';
			let loadingGIF = $('<img src="/images/loading.gif" style="max-height:30px" />');
			$(loadingGIF).insertAfter('#trend-search');

			$.getJSON(trend_api_host,{topic: topic},function(res){
				let trends_result = res.articles;
				$(loadingGIF).remove();
				let format_share_res = function(shares){
					//check if less than 1k
					if(shares < 1000){
						return shares;
					}else{
						return Math.floor(shares/1000) + 'K'
					}
				};

				let new_trends = trends_result.map(function(t){
					
					let trend_obj = {
					    "id": t.id,
						"title": t.title,
						"image": t.image_url,
						"body": t.excerpt,
						"when": format_time_ago(t.earliest_known_date),
						"source": t.source,
						"link":t.url,
						"author":'',
						"total_shares": format_share_res( t.share_count ),
						"fb_shares":0,
						"tw_shares": 0,
						"google_shares": 0,
						"video": 0
					};

					return trend_obj;
				});

				results.remove( results.models );
				results.add(new_trends);
				return new_trends;
			},
			function(error){
				console.log(error)
				console.log('couldnt connect to the endpoint/error');
			});

		};

		new create_message_view({el:"#create-alert", collection: results });
		let create_idea = new create_idea_modal({el: '#createIdea', collection: results });
		let share_trend = new share_trend_modal({el: '#shareTrendModal', collection: results });

		//get_trending_topics();

		new trend_results_view({el: '#trend-results', collection: results});

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

