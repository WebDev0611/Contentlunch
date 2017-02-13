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
            var popup_label_text = moment(this.$el.data('cell-date'), "YYYY-M-D").format('dddd, MMM Do YYYY');

            if (window.location.pathname.indexOf('weekly') >= 0 || window.location.pathname.indexOf('daily') >= 0) {
                popup_label_text = moment(this.$el.data('cell-date-time'), "YYYY-M-D-HHmmss").format('dddd, MMM Do YYYY HH:mm');
            }

            this.$el.find('.date-popup-label').text(popup_label_text);
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

            $('#task-start-date').val(moment(cell_date, "YYYY-M-D").format('YYYY-MM-DD HH:mm'));
            $('#task-due-date').val(moment(cell_date, "YYYY-M-D").add(1, 'days').format('YYYY-MM-DD HH:mm'));

            if (window.location.pathname.indexOf('weekly') >= 0 || window.location.pathname.indexOf('daily') >= 0) {
                cell_date = this.$el.data('cell-date-time');
                $('#task-start-date').val(moment(cell_date, "YYYY-M-D-HHmmss").format('YYYY-MM-DD HH:mm'));
                $('#task-due-date').val(moment(cell_date, "YYYY-M-D-HHmmss").add(1, 'days').format('YYYY-MM-DD HH:mm'));
            }

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

        // Declarations
        var ideas = new ideas_collection();
        var tasks = new task_collection();
        var my_content = new content_collection();


        // Maps
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
            t.assigned_to = [];
            t.assigned_users.forEach(function (i) {
                t.assigned_to.push(i.name);
            });

            return t;
        }

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
                c.content_status_text = '';
            }

            //Get content type slug
            var type = $.grep(types, function (e) {
                return e.id == c.content_type_id;
            });
            c.type_class = (type[0] != null) ? 'icon-type-' + type[0].provider.slug : 'primary icon-content-alert';

            c.author = '';
            c.authors.forEach(function (author) {
                c.author += author.name + '<br>';
            });
            if (c.author.profile_image) {
                c.user_image = c.author.profile_image;
            }

            return c;
        }

        // Fetch methods
        function fetchMyIdeas() {
            return $.ajax({
                url: '/ideas',
                method: 'get',
                headers: getJsonHeader(),
            })
        }

        function fetchMyTasks() {
            return $.ajax({
                url: '/api/tasks?account_tasks=1',
                method: 'get',
                headers: getJsonHeader(),
            })
        }

        function fetchMyContent() {
            return $.ajax({
                url: '/content/my',
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
                types.push(i.toJSON());
            });

            addCallback();
        });


        function addCallback() {

            $('#calendar-loading-gif').show();

            let myTasksPromise = fetchMyTasks();
            let myIdeasPromise = fetchMyIdeas();
            let myContentPromise = fetchMyContent();

            $.when(myTasksPromise, myIdeasPromise, myContentPromise).done((myTasksResponse, myIdeasResponse, myContentResponse) => {
                tasks.reset(myTasksResponse[0].data.map(task_map));
                ideas.reset(myIdeasResponse[0].map(idea_map));
                my_content.reset(myContentResponse[0].map(content_map).filter(function (c) {
                    return c.content_status != null;
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

            console.log('render done');
        }

        // Add new calendar
        $('#add-calendar-button').click(function () {
            add_calendar(function(){
                $('#createCalendarModal').modal('hide');
            });
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

