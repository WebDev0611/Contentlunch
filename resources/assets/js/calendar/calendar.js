'use strict';

/*
 ------ // Calendar JS // ----
 */

(function (window, document, $) {
    'use strict';

    const isDailyCalendar = () => window.location.pathname.indexOf('daily') >= 0;
    const isWeeklyCalendar = () => window.location.pathname.indexOf('weekly') >= 0;

    /* calendar item model */
    let calendar_item_model = Backbone.Model.extend({
        defaults: {
            type: 'idea',
            title: 'TITLE',
            date: new Date().getTime()
        }
    });

    /* calendar item collection */
    let calendar_item_collection = Backbone.Collection.extend({
        model: calendar_item_model,
        modelId: function (attrs) {
            return attrs.type + "-" + attrs.id;
        }
    });

    /* calendar item view */
    let calendar_item_view = Backbone.View.extend({
        tagName: 'li',
        events: {
            'click': 'open_item',
            'click .calendar-task-list-popover-close': 'close_item'
        },
        template: _.template($('#calendar-item-template').html()),
        initialize() {
            this.$el.append(this.template(this.model.attributes));
            this.render();
        },
        render() {
            //this.delegateEvents(['click']);
            return this;
        },
        open_item(event) {
            event.stopPropagation();
            this.$el.toggleClass('active');
            $('.calendar-task-list-popover').removeClass('open');
            this.$el.find('.calendar-task-list-popover').addClass('open');
        },
        close_item(event) {
            event.stopPropagation();
            this.$el.find('.calendar-task-list-popover').removeClass('open');
        }
    });

    /* the popup tool */
    let calendar_popup_tool = Backbone.View.extend({
        events: {},
        initialize: function () {
        },
        render: function () {
        }
    });

    /* the cell that holds the events */
    let calendar_container_view = Backbone.View.extend({
        events: {
            'click': 'show_tool',
            'mouseleave': 'hide_tool',
            'click .tool-add-task': 'show_task_modal',
            'click .tool-add-idea': 'show_idea_modal',
            'click .tool-add-content': 'show_content_modal',

            'mouseenter li span': 'add_active',
            'mouseleave li span': 'hide_active'
        },

        template: _.template($('#calendar-item-container').html()),

        initialize() {
            this.empty();
            this.$el.append(this.template());
        },

        render() {
            let view = this;
            this.collection.each(function (m) {
                let c_i = new calendar_item_view({model: m});
                switch (m.get('type')) {
                    case 'task':
                        view.$el.find('.calendar-task-list.t-task').append(c_i.$el);
                        break;
                    case 'idea':
                        view.$el.find('.calendar-task-list.t-idea').append(c_i.$el);
                        break;
                    case 'content':
                        view.$el.find('.calendar-task-list.t-content').append(c_i.$el);
                        break;
                }
            });

            $('<input>').attr({
                type: 'hidden',
                id: 'is_calendar_item',
                value: 'on',
                'data-id' : calendar.id
            }).appendTo('#addTaskModal, #createIdea, #addContentModal');

            return this;
        },

        empty() {
            this.$el.find('.calendar-schedule').remove();

            return this;
        },

        show_tool(event) {
            if (!this.$el.find('.calendar-schedule-dropdown-wrapper').length) {
                this.$el.append($('#calendar-dropdown-template').html());
            }

            let popup_label_text = moment(this.$el.data('cell-date'), "YYYY-M-D").format('dddd, MMM Do YYYY');

            if (isWeeklyCalendar() || isDailyCalendar()) {
                popup_label_text = moment(this.$el.data('cell-date-time'), "YYYY-M-D-HHmmss").format('dddd, MMM Do YYYY HH:mm');
            }

            this.$el.find('.date-popup-label').text(popup_label_text);
            this.$el.find('.calendar-schedule-dropdown-wrapper').fadeIn(100);
        },

        hide_tool(event) {
            this.$el.find('.calendar-schedule-dropdown-wrapper').remove();
        },

        add_active(event) {
            //console.log('mouse over');
            this.$el.addClass('active');
        },

        hide_active(event) {
            //console.log('hiding active');
            this.$el.removeClass('active');
        },

        show_task_modal() {
            let cell_date = this.$el.data('cell-date');

            if (isWeeklyCalendar() || isDailyCalendar()) {
                cell_date = this.$el.data('cell-date-time');
                $('#task-start-date').val(moment(cell_date, "YYYY-M-D-HHmmss").format('MM/DD/YYYY HH:mm'));
                $('#task-due-date').val(moment(cell_date, "YYYY-M-D-HHmmss").add(1, 'days').format('MM/DD/YYYY HH:mm'));
            } else {
                $('#task-start-date').val(moment(cell_date, "YYYY-M-D").format('MM/DD/YYYY HH:mm'));
                $('#task-due-date').val(moment(cell_date, "YYYY-M-D").add(1, 'days').format('MM/DD/YYYY HH:mm'));
            }

            $("#calendar-id").closest('.input-form-group').hide();

            $("#addTaskModal").modal({ backdrop: 'static' });
        },

        show_idea_modal() {
            $("#createIdea .form-delimiter").hide();
            this.append_date_input_field('idea_date', 'idea_date_info', 'createIdea');
            $("#idea-calendar-id").closest('.input-form-group').hide();
            $("#createIdea").modal({ backdrop: 'static' });
        },

        show_content_modal() {
            this.append_date_input_field('content_date', 'content_date_info', 'addContentModal');
            $("#addContentModal").modal({ backdrop: 'static' });
        },

        append_date_input_field(fieldId, fieldInfoId, selectorId) {
            let $field = this.get_field_element(fieldId, selectorId);
            let $fieldInfo =  this.get_field_info_element(fieldInfoId, selectorId);

            $field.val(this.formatDateValue());
            $fieldInfo.html(this.formatDateString($field.val()));
        },

        get_field_element(fieldId, selectorId) {
            let $field = $('#' + fieldId);

            if (!$field.length) {
                $field = $('<input>').attr({
                    type: 'hidden',
                    id: fieldId,
                }).appendTo('#' + selectorId);
            }

            return $field;
        },

        get_field_info_element(fieldInfoId, selectorId) {
            let $fieldInfo = $('#' + fieldInfoId);

            if (!$fieldInfo.length) {
                $fieldInfo = $('<h4>').attr({
                    id: fieldInfoId,
                }).prependTo('#' + selectorId + ' .sidemodal-container');
            }

            return $fieldInfo;
        },

        formatDateValue() {
            let cell_date = this.$el.data('cell-date');

            if (isWeeklyCalendar() || isDailyCalendar()) {
                cell_date = this.$el.data('cell-date-time');
                return moment(cell_date, "YYYY-M-D-HHmmss").format('YYYY-MM-DD HH:mm:ss');
            } else {
                return moment(cell_date, "YYYY-M-D").format('YYYY-MM-DD') + ' ' + moment().format('HH:mm:ss');
            }
        },

        formatDateString(value) {
            return moment(value, "YYYY-MM-DD HH:mm:ss").format('MM/DD/YY [at] h:mm a');
        }
    });

    $(function () {
        let my_campaigns = new campaign_collection(campaigns.map(function (campaign) {
            campaign.date = campaign.start_date;
            campaign.type = 'campaign';

            return campaign;
        }));

        // Declarations
        let ideas = new ideas_collection();
        let tasks = new task_collection();
        let my_content = new content_collection();

        // Maps
        function idea_map(idea) {
            idea.date = idea.created_at;
            idea.type = 'idea';
            idea.title = idea.name;
            idea.author = idea.user.name;
            idea.details_url = '/idea/' + idea.id;
            idea.explanation = idea.text;

            if (idea.user.profile_image) {
                idea.user_image = idea.user.profile_image;
            }

            return idea;
        }

        function task_map(task) {
            task.date = task.start_date;
            task.type = 'task';
            task.title = task.name;
            task.author = task.user.name;
            task.details_url = '/task/show/' + task.id;
            task.due = moment(task.due_date).format('MM-DD-YY');

            if (task.user.profile_image) {
                task.user_image = task.user.profile_image;
            }

            task.assigned_to = [];
            task.assigned_users.forEach(function (i) {
                task.assigned_to.push(i.name);
            });

            return task;
        }

        function content_map(content) {
            content.date = content.created_at;
            content.type = 'content';
            content.details_url = '/edit/' + content.id;

            // Limit popup text to 30 words
            let str_text = '';
            if (/<[a-z][\s\S]*>/i.test(content.body)) {
                // If text contains formatted html
                str_text = jQuery(content.body).text();
            } else {
                str_text = content.body;
            }

            content.explanation = str_text != null
                ? (str_text.split(" ").splice(0, 30).join(" ") + '...')
                : '';

            content.due = moment(content.due_date).format('MM-DD-YY');

            // Published status
            if (content.content_status_id == '4') {
                content.content_status = 'archived';
                content.content_status_text = 'archived';
            }
            else if (content.content_status_id == '3') {
                content.content_status = 'published';
                content.content_status_text = 'published';
                content.date = content.updated_at;
            }
            else if (content.content_status_id == '2') {
                content.content_status = 'ready_published';
                content.content_status_text = 'ready for publishing';
            }
            else if (content.content_status_id == '1') {
                content.content_status = 'written';
                content.content_status_text = 'being written';
            } else {
                content.content_status = null;
                content.content_status_text = '';
            }

            //Get content type slug
            let type = $.grep(types, function (e) {
                return e.id == content.content_type_id;
            });

            content.type_class = 'primary icon-content-alert ';
            if (type[0] != null && type[0].slug != null) {
                content.type_class += 'icon-' + type[0].slug;
            }

            content.author = '';
            content.authors.forEach(function (author) {
                content.author += author.name + '<br>';
            });

            if (content.author.profile_image) {
                content.user_image = content.author.profile_image;
            }

            return content;
        }

        // Fetch methods
        function fetchMyIdeas() {
            return $.ajax({
                url: '/calendar/' + calendar.id + '/ideas',
                method: 'get',
                headers: getJsonHeader(),
            })
        }

        function fetchMyTasks() {
            return $.ajax({
                url: '/calendar/' + calendar.id + '/tasks',
                method: 'get',
                headers: getJsonHeader(),
            })
        }

        function fetchMyContent() {
            return $.ajax({
                url: '/calendar/' + calendar.id + '/contents',
                method: 'get',
                headers: getJsonHeader(),
            })
        }


        // Add new task
        $('#add-task-button').click(function () {
            add_task(addCallback);
        });

        // Content types
        let content_types = new content_type_collection();
        content_types.fetch().then(response => content_types.reset(response));
        let types = [];

        content_types.on('update', function (type) {
            type.forEach(function (i) {
                let el = i.toJSON();
                el.slug = el.name.toLowerCase().split(' ').join('-');
                types.push(el);
            });

            addCallback();
        });

        function addCallback(new_calendar = null, has_calendar = false) {

            let this_calendar = (new_calendar != null && has_calendar == true) ? new_calendar : get_this_calendar();

            $('#calendar-loading-gif').show();

            let myTasksPromise = fetchMyTasks();
            let myIdeasPromise = fetchMyIdeas();
            let myContentPromise = fetchMyContent();

            $.when(myTasksPromise, myIdeasPromise, myContentPromise).done((myTasksResponse, myIdeasResponse, myContentResponse) => {
                tasks.reset(myTasksResponse[0].data.map(task_map).filter(function () {
                    return this_calendar.show_tasks == "1";
                }));
                ideas.reset(myIdeasResponse[0].map(idea_map).filter(function () {
                    return this_calendar.show_ideas == "1";
                }));
                my_content.reset(myContentResponse[0].map(content_map).filter(function (c) {
                    var has_content_type = $.grep(this_calendar.content_types, function (e) {
                        return e.id == c.content_type_id;
                    });
                    return c.content_status != null && has_content_type.length > 0;
                }));

                my_campaigns.reset();

                tasks.forEach(function (t) {
                    my_campaigns.add(t);
                });
                ideas.forEach(function (t) {
                    my_campaigns.add(t);
                });
                my_content.forEach(function (t) {
                    my_campaigns.add(t);
                });

                renderCalendarItems(my_campaigns);

                $('#calendar-loading-gif').fadeOut();
            });

            $('#addTaskModal').modal('hide');
        }


        // RENDER
        function renderCalendarItems(calendar_items) {
            let day_containers = {};
            let hour_containers = {};

            calendar_items.each(function (i) {
                let d = moment(i.get('date')).format('YYYY-M-D');
                let dt = moment(i.get('date')).format('YYYY-M-D') + '-' + moment(i.get('date')).format('HH') + '0000';
                if (day_containers[d]) {
                    day_containers[d].push(i);
                } else {
                    day_containers[d] = [i];
                }
                if (hour_containers[dt]) {
                    hour_containers[dt].push(i);
                } else {
                    hour_containers[dt] = [i];
                }
            });

            let cal_views = {};
            let page_cell_sel = 'tbody.calendar-month-days td';

            if (isWeeklyCalendar()) {
                page_cell_sel = 'tbody.calendar-week-hours td';
            }

            if (isDailyCalendar()) {
                page_cell_sel = 'tbody.calendar-day td';
            }

            $(page_cell_sel).each(function (i, c) {
                let d_string = $(c).data('cell-date') || $(c).data('cell-date-time');

                if (d_string) {
                    let sel = '#date-' + d_string;
                    let col_set_group = day_containers[d_string] || hour_containers[d_string] || [];
                    let col = new calendar_item_collection(col_set_group);

                    cal_views[d_string] = new calendar_container_view({el: sel, collection: col});
                    cal_views[d_string].render();
                }
            });

            console.log('render done');
        }

        // Add new calendar
        $('#add-calendar-button').click(function () {
            add_calendar(function () {
                $('#createCalendarModal').modal('hide');
            });
        });

        // Ideas
        $('.save-idea').click(function () {
            store_idea('active', addCallback);
        });
        $('.park-idea').click(function () {
            store_idea('parked', addCallback);
        });

        // Content
        tinymce.init({
            selector: 'textarea.wysiwyg',  // change this value according to your HTML
            plugin: 'a_tinymce_plugin',
            a_plugin_option: true,
            a_configuration_option: 400
        });

        $('#add-content-button').click(function () {
            store_content(addCallback);
        });

        // Invites
        $('#invite-guests-button').click(function () {
            send_invites();
        });

        // Filter
        reset_filter();

        let multiple_select = $('.multipleSelect');
        multiple_select.fastselect();

        $('#apply-filters').click(function () {
            let new_calendar = get_filtered_calendar(multiple_select);
            $("#filterModal").modal('hide');
            addCallback(new_calendar, true);
        });

        $('#clear-filters-btn').click(function () {
            $("#filterModal").modal('hide');
            addCallback();
            reset_filter();
        });

        $('#filter-plus-btn').click(function () {
            $('.fstElement.fstMultipleMode').toggleClass('fstResultsOpened fstActive');
        });

        $('#save-as-new').click(function () {
            let new_calendar = get_filtered_calendar(multiple_select);
            $('#show_tasks, #show_ideas, .checkbox-content-types input').removeAttr('checked');
            if (new_calendar.show_tasks == "1") {
                $('#show_tasks').attr('checked', 'checked');
            }
            if (new_calendar.show_ideas == "1") {
                $('#show_ideas').attr('checked', 'checked');
            }
            $.each(new_calendar.content_types, function (key, type) {
                $('#content_type_' + type.id).attr('checked', 'checked');
            });
            $("#filterModal").modal('hide');
            $("#createCalendarModal").modal('show');
            $('#calendar_name').focus();
        });

        /*
         var drop_down_calendar_tool = Backbone.View.extend({
         events: {},
         initialize: function () {
         },
         render: function () {
         },
         });
         */
    });

})
(window, document, jQuery);
