/* 
------ // Calendar JS // ----
*/

(function(window,document,$){
    'use strict';

    var dummy_calendar_data = [
        {
            type:'idea',
            title: 'Lorem ispum',
            date: new Date().getTime() + (1000 * 60 * 60 * 24 *3)
        },
        {
            type:'task',
            title: 'Content mix: post 3 blogs...',
            date: new Date().getTime() + (1000 * 60 * 60 * 24 *5)
        }
    ];

    /* calendar item model */
    var calendar_item_model = Backbone.Model.extend({
        defaults:{
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
        events:{
            'click': 'open_item',
        },
        template: _.template( $('#calendar-item-template').html() ),
        initialize:function(){
            this.$el.append( this.template( this.model.attributes ) );
            this.render();
        },
        render: function(){
           // this.delegateEvents(['click']);
            return this;
        },
        open_item: function(event){
            event.stopPropagation();
            this.$el.toggleClass('active');
            this.$el.find('.calendar-task-list-popover').toggleClass('open');
        }

    });

    /* the popup tool */
    var calendar_popup_tool = Backbone.View.extend({
        events:{

        },
        initialize: function(){},
        render: function(){}
    });

    /* the cell that holds the events */
    var calendar_container_view = Backbone.View.extend({
        events:{
            'click':'show_tool',
            'mouseleave':'hide_tool',
            'click .tool-add-task': 'show_task_modal',

            'mouseenter li span': 'add_active',
            'mouseleave li span': 'hide_active'
        },
        template: _.template( $('#calendar-item-container').html() ),
        initialize: function(){
            this.$el.append( this.template() );
            this.render();
        },
        render: function(){
            var view = this;
            this.collection.each(function(m){
                var c_i = new calendar_item_view({model:m});
                view.$el.find('.calendar-task-list').append( c_i.$el );
            });
            return this;
        },
        show_tool: function(event){
            //tool needs to be a view
            console.log( this.$el.data('cell-date') );
            this.$el.append( $('#calendar-dropdown-template').html() );
            this.$el.find('.date-popup-label').text('Wed, Mar 4, 2016, 01 PM');

            this.$el.find('.calendar-schedule-dropdown-wrapper').fadeIn(100);
        },
        hide_tool: function(event){
            this.$el.find('.calendar-schedule-dropdown-wrapper').remove();
        },
        add_active: function(event){
            console.log('mouse over');
            this.$el.addClass('active');
        },
        hide_active: function(event){
            console.log('hiding active');
            this.$el.removeClass('active');
        },
        show_task_modal: function(){
            $("#addTaskModal").modal('show');
            
            //$('#task-start-date').val( );
            //console.log(this.collection);
            // var dateStr = this.$el.attr('id').split('-');
            // $('#task-start-date').val( dateStr[1] + '-' + dateStr[2] + '-' + dateStr[3] );
            // $("#addTaskCalendar").modal('show');
        }
       
    });


    $(function(){
        var my_campaigns = new campaign_collection(campaigns.map(function(c){
            c.date = c.start_date;
            c.type = 'campaign';
            return c;
        }));

        // dummy_calendar_data.forEach(function(dcd){
        //     my_campaigns.add(dcd);
        // });

        tasks.map(function(t){
            t.date = t.start_date;
            t.type = 'task';
            t.title = t.name;
            return t;
        }).forEach(function(t){
            my_campaigns.add(t);
        });

        var calendar_items = my_campaigns; //new calendar_item_collection( my_campaigns );
        var day_containers = {};
        var hour_containers = {};

        calendar_items.each(function(i){
            var d = moment(i.get('date')).format('YYYY-M-D');
            var dt = moment(i.get('date')).format('YYYY-M-D') + '-' + moment(i.get('date')).format('HH') + '0000';
            if( day_containers[d] ){
                day_containers[d].push(i);
            }else{
                day_containers[d] = [i];
            }
            if( hour_containers[dt] ){
                hour_containers[dt].push(i);
            }else{
                hour_containers[dt] = [i];
            }
        });
        var cal_views = {};
        var page_cell_sel = 'tbody.calendar-month-days td';
        if(window.location.pathname.indexOf('weekly') >= 0 ){
            page_cell_sel = 'tbody.calendar-week-hours td';
        }
        if(window.location.pathname.indexOf('daily') >= 0 ){
            page_cell_sel = 'tbody.calendar-day td';
        }

        $(page_cell_sel).each(function(i,c){
            var d_string = $(c).data('cell-date') || $(c).data('cell-date-time');
            if(d_string){
                var sel = '#date-' + d_string;
                var col_set_group = day_containers[ d_string ] || hour_containers[ d_string ] || [];
                var col = new calendar_item_collection( col_set_group );
                cal_views[ d_string ] = new calendar_container_view({el: sel, collection: col });
                //cal_views[ d_string ].render();
            }
        }); 

        // $('#task-start-date').datetimepicker({
        //     format: 'YYYY-MM-DD HH:mm:ss',
        //     sideBySide: true,
        // });

        // $('#task-due-date').datetimepicker({
        //     format: 'YYYY-MM-DD HH:mm:ss',
        //     sideBySide: true,
        // });

        // var  add_task = function(){
        //     var task_data = {
        //         name: $('#task-name').val(),
        //         start_date: $('#task-start-date').val(),
        //         due_date: $('#task-due-date').val(),
        //         explanation: $('#task-explanation').val(),
        //         url: $('#task-url').val()
        //     };

        //     //need proper validation here
        //     if(task_data.name.length>2){
        //         $.ajax({
        //             url: '/task/add',
        //             type: 'post',
        //             data: task_data,
        //             headers: {
        //                 'X-CSRF-TOKEN': $('input[name=_token]').val()
        //             },
        //             dataType: 'json',
        //             success:function(res){
        //                 console.log(res);
                        
        //             }
        //         });
        //     }
        // };
        // $('#add-task-button').click(add_task);
    var drop_down_calendar_tool = Backbone.View.extend({
        events:{},
        initialize: function(){},
        render: function(){},

    });
    
    var calendar_idea_view = Backbone.View.extend({
        events: {
            "click":"add_to_calendar"
        },
        tagName: 'li',
        template: _.template( $('#calendar-idea-template').html() ),
        initialize: function(){},
        render: function(){
            console.log(this.model.attributes);
            this.$el.html( this.template( this.model.attributes) );
            return this;
        },
        add_to_calendar: function(){

        }
    });

    var calendar_content_view = Backbone.View.extend({
        events:{
            "click": "add_to_calendar"
        },
        tagName: 'li',
        template: _.template( $('#calendar-content-template').html() ),
        initialize: function(){},
        render: function(){
            console.log(this.model.attributes);
            this.$el.html( this.template( this.model.attributes) );
            return this;
        },
        add_to_calendar: function(){

        }
    });

    var my_ideas = new ideas_collection();
    my_ideas.on('update',function(c){
        $('#calendar-idea-list').html('');
        my_ideas.each(function(m){
            var i_v = new calendar_idea_view({model:m});
            $('#calendar-idea-list').append( i_v.render().$el );
        });
      console.log(c.toJSON());
    });
    my_ideas.fetch();


    var my_content = new content_collection();
    my_content.on('update',function(c){
        $('#calendar-content-list').html('');
        my_content.each(function(m){
            var c_v = new calendar_content_view({model:m});
            $('#calendar-content-list').append( c_v.render().$el );
        });
      console.log(c.toJSON());
    });
    my_content.fetch();
  });


})(window,document,jQuery); 

