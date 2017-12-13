jQuery(document).ready(function($){

	function payumoney_suvmit(){
		if( $('.payu-submit-click').length ){
			$('.payu-submit-click').submit();
		}
	}
	payumoney_suvmit();

	$(document).on( 'click', '.payu-additional-info', function(){
		$.ajax({
			url: ajaxurl,
			method: 'POST',
			data: $(this).parents('form').serialize(),
			success: function( response ){
				$('.payumoney-wrap').html( $('<div>'+response+'</div>').find( '.payumoney-wrap' ).html() );
				payumoney_suvmit();
			}
		});
	});
});