/* misc js -- global or sitewide methods here */

//handles the create modal for the site
$(function(){
	$('.btn-create').click(function(){
		$('body').toggleClass('showcreate');
	});
	
	$('div.create-overlay').click(function(){
		$('body').toggleClass('showcreate'); 
	});

	$('div.create-overlay li').click(function(event){
 		event.stopPropagation();
	});
});

var format_time_ago = function(time){
	var cur = moment();
	var hours_ago = cur.diff( moment(time*1000) ,'hours');
	var days_ago = cur.diff( moment(time*1000) ,'days');
	var minutes_ago = cur.diff( moment(time*1000) ,'minutes');

	if(days_ago > 0){
		return days_ago + ' DAYS AGO';
	}else if(hours_ago > 0){
		return hours_ago + ' HOURS AGO';
	}else if(minutes_ago > 0){
		return minutes_ago + ' MINUTES AGO';
	}else{
		return 'JUST NOW';
	}
};