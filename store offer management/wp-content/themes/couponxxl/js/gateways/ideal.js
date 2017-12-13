jQuery(document).ready(function($){
	/* PAY WITH IDEAL */
	$(document).on( 'click', '.ideal_bank', function(){
		$.ajax({
			url: ajaxurl,
			data: $(this).parents('form').serialize(),
			method: 'POST',
			success: function(response){
				if( response.indexOf('http') > -1 ){
					window.location.href = response;
				}
				else{
					$('.ajax-response').html( response );
				}
			}
		})
	});	
});