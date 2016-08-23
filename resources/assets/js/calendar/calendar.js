/* 
------ // Calendar JS // ----
*/

(function(window,document,$){
    'use strict';

    var dummy_calendar_data = [
        {
            type:'idea',
            title: 'Content mix: post 3 blogs...',
            date: 1471919205000
        },
        {
            type:'task',
            title: 'Post 16 social postings',
            date: 1471419205000
        },
        {
            type:'task',
            title: 'Post 16 social postings',
            date: 1471119205000
        },
        {
            type:'task',
            title: 'Post 16 social postings',
            date: 1471019205000
        },
        {
            type:'task',
            title: 'Post 16 social postings',
            date: 1470419205000
        },
        {
            type:'idea',
            title: 'Post 16 social postings',
            date: 1470119205000
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
            'click li': 'open_item'
        },
        template: _.template( $('#calendar-item-template').html() ),
        render: function(){
            this.$el.append( this.template(this.model.attributes ) );
            return this;
        },
        open_item: function(){
            console.log('clicked calendar item!' + this.model.attributes);
        }
    });

    /* the cell that holds the events */
    var calendar_container_view = Backbone.View.extend({
        events:{
            'click':'clicked_test'
        },
        template: _.template( $('#calendar-item-container').html() ),
        initialize: function(){
            console.log('init' + this.el );
            this.$el.append(this.template());
        },
        render: function(){
            var view = this;
            this.collection.each(function(m){
                var c_i = new calendar_item_view({model:m});
                console.log(c_i);
                view.$el.find('.calendar-task-list').append( c_i.render().$el );
            });
            return this;
        },
        clicked_test: function(){
            console.log('clicked!');
            console.log( this.$el.attr('id') );
            console.log(this.collection.toJSON());
        }
    });

    var activeCell = null;
    var appendTaskButton = function(cell){
        var taskButton = $('#calendar-dropdown-template').html();
        $(cell).append(taskButton);
    };
    var removeTaskButton = function(cell){
        $(cell).find('.calendar-schedule-dropdown-wrapper').fadeOut(200,function(){
            $(this).remove();
        });
    };

    var showCalActionOverlay = function(identifier){
        var selCellVal = $(this).data(identifier);
        var selCell = $(this);
        if( $(activeCell).data(identifier) !== selCellVal && !$(this).attr('disabled') ){
            appendTaskButton(selCell);
            removeTaskButton(activeCell);
            activeCell = selCell;
        }
    };
    var activateMonthly = function(){
        showCalActionOverlay.call(this,'cell-date');
    };
    var activateWeekly = function(){
        showCalActionOverlay.call(this,'cell-date-time');
    };      
    var activateDaily = function(){
        showCalActionOverlay.call(this,'cell-time');
    };

    $('.calendar-month-days td').mouseenter(activateMonthly);
    $('.calendar-week-hours td').mouseenter(activateWeekly);
    $('.calendar-day td').mouseenter(activateDaily);


    $(function(){
        var calendar_items = new calendar_item_collection(dummy_calendar_data);
        // calendar_items.each(function(i){
        //     new calendar_item_view({ model: i});
        // });

        var day_containers = {};

        calendar_items.each(function(i){
            var d = moment(i.get('date')).format('YYYY-M-DD');
            if( day_containers[d] ){
                day_containers[d].push(i);
            }else{
                day_containers[d] = [i];
            }
        });
        console.log(day_containers);
        //map calendar items to days
        //selector for the container div/ul template
        //calendar-item-container

        //get all the table cells and set up view for each
       // new calendar_container_view({el: sel})
        var cal_views = {};
        $('tbody.calendar-month-days td').each(function(i,c){
            var d_string = $(c).data('cell-date');
            if(d_string){
                var sel = '#date-' + d_string;
                day_containers[ d_string ] = day_containers[ d_string ] || [];

                var col = new calendar_item_collection( day_containers[ d_string ] );
                cal_views[ d_string ] = new calendar_container_view({el: sel, collection: col });
                cal_views[ d_string ].render();
            }
        }); 
    });
})(window,document,jQuery); 

