/* 
 ------ // Calendar JS // ----
 */

(function (window, document, $) {
    'use strict';

    /* calendar item model */
    var calendar_item_model = Backbone.Model.extend({
        defaults: {
            type: 'idea',
            title: 'TITLE',
            date: new Date().getTime()
        }
    });

    /* calendar item collection */
    var calendar_item_collection = Backbone.Collection.extend({
        model: calendar_item_model
    });

    /* calendar item view */
    var calendar_item_view = Backbone.View.extend({
        tagName: 'li',
        events: {
            'click': 'open_item',
        },
        template: _.template($('#calendar-item-template').html()),
        initialize: function () {
            this.$el.append(this.template(this.model.attributes));
            this.render();
        },
        render: function () {
            // this.delegateEvents(['click']);
            return this;
        },
        open_item: function (event) {
            event.stopPropagation();
            this.$el.toggleClass('active');
            this.$el.find('.calendar-task-list-popover').toggleClass('open');
        }

    });

    /* the popup tool */
    var calendar_popup_tool = Backbone.View.extend({
        events: {},
        initialize: function () {
        },
        render: function () {
        }
    });

    /* the cell that holds the events */
    var calendar_container_view = Backbone.View.extend({
        events: {
            'click': 'show_tool',
            'mouseleave': 'hide_tool',
            'click .tool-add-task': 'show_task_modal',

            'mouseenter li span': 'add_active',
            'mouseleave li span': 'hide_active'
        },
        template: _.template($('#calendar-item-container').html()),
        initialize: function () {
            this.empty();
            this.$el.append(this.template());
        },
        render: function () {
            var view = this;
            this.collection.each(function (m) {
                var c_i = new calendar_item_view({model: m});
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
            return this;
        },
        empty: function () {
            var view = this;
            view.$el.find('.calendar-schedule').remove();
            return this;
        },
        show_tool: function (event) {
            this.$el.append($('#calendar-dropdown-template').html());
            this.$el.find('.date-popup-label').text(moment(this.$el.data('cell-date'), "YYYY-M-D").format('dddd, MMM Do YYYY'));
            this.$el.find('.calendar-schedule-dropdown-wrapper').fadeIn(100);
        },
        hide_tool: function (event) {
            this.$el.find('.calendar-schedule-dropdown-wrapper').remove();
        },
        add_active: function (event) {
            //console.log('mouse over');
            this.$el.addClass('active');
        },
        hide_active: function (event) {
            //console.log('hiding active');
            this.$el.removeClass('active');
        },
        show_task_modal: function () {
            var cell_date = this.$el.data('cell-date');

            $('#task-start-date').val(moment(cell_date, "YYYY-M-D").format('YYYY-MM-DD'));
            $('#task-due-date').val(moment(cell_date, "YYYY-M-D").add(1, 'days').format('YYYY-MM-DD'));
            $("#addTaskModal").modal('show');

            // $("#addTaskCalendar").modal('show');
        }

    });


    $(function () {
        var my_campaigns = new campaign_collection(campaigns.map(function (c) {
            c.date = c.start_date;
            c.type = 'campaign';
            return c;
        }));

        // Tasks
        tasks = new task_collection(tasks.map(task_map));
        tasks.forEach(function (t) {
            my_campaigns.add(t);
        });

        $('#add-task-button').click(function () {
            add_task(addTaskCallback);
        });

        function addTaskCallback(task) {
            let myTasksPromise = fetchMyTasks();
            let myIdeasPromise = fetchMyIdeas();

            $.when(myTasksPromise, myIdeasPromise).done((myTasksResponse, myIdeasResponse) => {
                tasks.reset(myTasksResponse[0].data.map(task_map));
                my_ideas.reset(myIdeasResponse[0].map(idea_map));

                my_campaigns.reset();
                tasks.forEach(function (t) {
                    my_campaigns.add(t);
                });
                my_ideas.forEach(function (t) {
                    my_campaigns.add(t);
                });

                renderCalendarItems(my_campaigns);
            });

            $('#addTaskModal').modal('hide');
        }

        function fetchMyTasks() {
            return $.ajax({
                url: '/api/tasks',
                method: 'get',
                headers: getJsonHeader(),
            })
        }

        function task_map(t) {
            t.date = t.start_date;
            t.type = 'task';
            t.title = t.name;
            t.author = t.user.name;
            t.details_url = '/task/show/' + t.id;
            t.due = moment(t.due_date).format('MM/DD/YYYY');
            t.status = t.status;
            if (t.user.profile_image) {
                t.user_image = t.user.profile_image;
            }

            return t;
        }


        // Ideas
        var my_ideas = new ideas_collection();
        my_ideas.fetch().then(response => my_ideas.reset(response));

        my_ideas.on('update', function (ideas) {
            ideas = new task_collection(ideas.toJSON().map(idea_map));
            ideas.forEach(function (i) {
                my_campaigns.add(i);
            });
            renderCalendarItems(my_campaigns);
        });

        function idea_map(i) {
            i.date = i.created_at;
            i.type = 'idea';
            i.title = i.name;
            i.author = i.user.name;
            i.details_url = '/idea/' + i.id;
            i.explanation = i.text;
            if (i.user.profile_image) {
                i.user_image = i.user.profile_image;
            }

            return i;
        }

        function fetchMyIdeas() {
            return $.ajax({
                url: '/ideas',
                method: 'get',
                headers: getJsonHeader(),
            })
        }


        // Content
        let cotent_types = new content_type_collection();
        cotent_types.fetch().then(response => cotent_types.reset(response));
        let types = [];

        cotent_types.on('update', function (type) {
            type.forEach(function (i) {
                types.push(i.toJSON());
            });
        });

        var my_content = new content_collection();
        my_content.fetch().then(response => my_content.reset(response));
        my_content.on('update', function (content) {
            // Exclude wrong content
            content = new task_collection(content.toJSON().map(content_map).filter(function (c) {
                return c.content_status !== null;
            }));
            content.forEach(function (i) {
                my_campaigns.add(i);
            });
            renderCalendarItems(my_campaigns);
        });

        function content_map(c) {
            c.date = c.created_at;
            c.type = 'content';
            c.details_url = '/edit/' + c.id;
            c.explanation = c.body.substr(0, 140) + ' ...';
            c.due = moment(c.due_date).format('MM/DD/YYYY');

            // Published status
            if (c.published == '1') {
                c.content_status = 'published';
                c.content_status_text = 'published';
                c.date = c.updated_at;
            }
            else if (c.ready_published == '1') {
                c.content_status = 'ready_published';
                c.content_status_text = 'ready for publishing';
            }
            else if (c.written == '1') {
                c.content_status = 'written';
                c.content_status_text = 'being written';
            } else {
                c.content_status = null;
            }

            // Get content type slug
            var type = $.grep(types, function (e) {
                return e.id == c.content_type_id;
            });
            c.type_class = (type[0] != null) ? 'icon-type-' + type[0].provider.slug : 'primary icon-content-alert';

            // TODO content user
            c.author = 'Ivo'; //i.user.name;
            // if (i.user.profile_image) {
            //     i.user_image = i.user.profile_image;
            // }

            return c;
        }

        // Render
        // var calendar_items = my_campaigns; //new calendar_item_collection( my_campaigns );
        renderCalendarItems(my_campaigns);

        function renderCalendarItems(calendar_items) {

            var day_containers = {};
            var hour_containers = {};

            calendar_items.each(function (i) {
                var d = moment(i.get('date')).format('YYYY-M-D');
                var dt = moment(i.get('date')).format('YYYY-M-D') + '-' + moment(i.get('date')).format('HH') + '0000';
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
            var cal_views = {};
            var page_cell_sel = 'tbody.calendar-month-days td';
            if (window.location.pathname.indexOf('weekly') >= 0) {
                page_cell_sel = 'tbody.calendar-week-hours td';
            }
            if (window.location.pathname.indexOf('daily') >= 0) {
                page_cell_sel = 'tbody.calendar-day td';
            }

            $(page_cell_sel).each(function (i, c) {
                var d_string = $(c).data('cell-date') || $(c).data('cell-date-time');
                if (d_string) {
                    var sel = '#date-' + d_string;
                    var col_set_group = day_containers[d_string] || hour_containers[d_string] || [];
                    var col = new calendar_item_collection(col_set_group);

                    cal_views[d_string] = new calendar_container_view({el: sel, collection: col});
                    cal_views[d_string].render();
                }
            });
        }

        $('#task-start-date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            sideBySide: true,
        });

        $('#task-due-date').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss',
            sideBySide: true,
        });

        var drop_down_calendar_tool = Backbone.View.extend({
            events: {},
            initialize: function () {
            },
            render: function () {
            },

        });
    });

})(window, document, jQuery);

