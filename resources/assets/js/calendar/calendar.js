/* 
------ // Calendar JS // ----
*/

(function(window,document,$){
    'use strict';

    var dummy_calendar_data = [
        {
            type:'idea',
            title: 'Lorem ispum',
            date: 1471919205000
        },
        {
            type:'task',
            title: 'Content mix: post 3 blogs...',
            date: 1471918205000
        },
        {
            type:'task',
            title: 'Post 16 social postings',
            date: 1471414205000
        },
        {
            type:'task',
            title: 'Post 16 social postings',
            date: 1471119005000
        },
        {
            type:'task',
            title: 'Post 16 social postings',
            date: 1471019005000
        },
        {
            type:'task',
            title: 'Post 10 social postings',
            date: 1470419005000
        },
        {
            type:'idea',
            title: 'Post 20 social postings',
            date: 1470119205000
        },
        {
            type:'idea',
            title: 'Post 1 social postings',
            date: 1470119205000
        },
        {
            type:'idea',
            title: 'Post 5 social postings',
            date: 1470119205000
        },
        {
            type:'task',
            title: 'Post 9 social postings',
            date: 1470119205000
        },
        {
            type:'task',
            title: 'Post 15 social postings',
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
            console.log('clicked');
            this.$el.toggleClass('active');
            this.$el.find('.calendar-task-list-popover').toggleClass('open');
        }
    });

    /* the cell that holds the events */
    var calendar_container_view = Backbone.View.extend({
        events:{
            'mouseenter li': 'add_active',
            'mouseleave li': 'hide_active'
        },
        template: _.template( $('#calendar-item-container').html() ),
        initialize: function(){
            console.log(this);
            this.$el.append( this.template() );
            this.$el.append( $('#calendar-dropdown-template').html() );
            this.$el.find('.date-popup-label').text('Wed, Mar 4, 2016, 01 PM');
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
            event.stopPropagation();

            this.$el.find('.calendar-schedule-dropdown-wrapper').fadeIn(100);
        },
        hide_tool: function(event){
            event.stopPropagation();

            this.$el.find('.calendar-schedule-dropdown-wrapper').fadeOut(100);
        },
        add_active: function(event){
            event.stopPropagation();
            console.log(this);
            console.log('mouse over');
            this.$el.addClass('active');
        },
        hide_active: function(event){
            console.log(this);
            event.stopPropagation();
            console.log('hiding active');
            this.$el.removeClass('active');
        }
    });

    $(function(){
        var calendar_items = new calendar_item_collection(dummy_calendar_data);
        var day_containers = {};
        var hour_containers = {};

        calendar_items.each(function(i){
            var d = moment(i.get('date')).format('YYYY-M-DD');
            var dt = moment(i.get('date')).format('YYYY-M-DD') + '-' + moment(i.get('date')).format('HH') + '0000';
            if( day_containers[d] ){
                day_containers[d].push(i);
                hour_containers[dt].push(i);
            }else{
                day_containers[d] = [i];
                hour_containers[dt] = [i];
            }
        });
        console.log(hour_containers);
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
    });
})(window,document,jQuery); 

