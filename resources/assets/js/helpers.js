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