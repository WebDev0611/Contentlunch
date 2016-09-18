(function($){
 
 $(function(){
 	var campaign_form = new new_campaign_view({el: '#create-campaign-form'});
 	var d = campaign;
 	if(d){
 		console.log(d);
 		campaign_form.d = d;
 	}
 });

})(jQuery);