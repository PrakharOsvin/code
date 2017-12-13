<?php
/*
    Template Name: Checkout
*/
get_header();
the_post();

global $couponxxl_cart;
?>
	<section>
		<div class="container">
			<div class="ajax-cart-wrap">
				<?php
				if ( ! empty( $_GET['action'] ) && $_GET['action'] == 'gateway-return' && ! empty( $_GET['gateway'] ) ) {
					do_action( 'couponxxl_process_response', $_GET['gateway'] );
				} else {
					$order_key = ! empty( $_GET['order_key'] ) ? $_GET['order_key'] : '';
					$package   = ! empty( $_GET['package'] ) ? $_GET['package'] : '';
					if ( empty( $package ) ) {
						$couponxxl_cart->initiate_payment( $order_key );
					} else {
						couponxxl_generate_credit_payments();
					}
				}
				?>
			</div>
		</div>
	</section>
<?php get_footer(); ?>