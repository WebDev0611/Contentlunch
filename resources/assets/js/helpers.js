/* misc js */
$(
function(){
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