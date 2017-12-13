jQuery(document).ready(function($){
	/* PAY WITH STRIPE */
	if( $('.stripe-payment').length > 0 ){
		var offer_id;
		var handler = StripeCheckout.configure({
		    key: $('.stripe-payment').attr('data-pk'),
		    token: function(token) {
		    	$('.stripe-response').html( '<div class="alert alert-info">'+$('.stripe-payment').data('genearting_string')+'</div>' );
		    	var payment_for = '';
		    	var action = 'pay_with_stripe';
				$.ajax({
					url: ajaxurl,
					method: 'POST',
					data: {
						action: action,
						token: token,
						offer_id: offer_id,
						transient: $('.stripe-payment').data('transient')
					},
					success: function( response ){
						$('.stripe-response').html( response );
					}
				});
		    }
		});		
		handler.open({
			name: $(this).attr('data-name'),
			description: $(this).attr('data-description'),
			amount: $(this).attr('data-amount'),
			currency: $(this).attr('data-currency')
		});
		offer_id = $(this).attr('data-offer_id');
		// Close Checkout on page navigation
		$(window).on('popstate', function() {
			handler.close();
		});		
	}
});