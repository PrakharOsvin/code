<?php
$views = get_post_meta( get_the_ID(), 'offer_views', true );
if ( empty( $views ) ) {
	$views = 1;
} else {
	$views ++;
}
update_post_meta( get_the_ID(), 'offer_views', $views );
$offer_type = couponxxl_get_the_offer_type();
$offer = couponxxl_get_offer_meta( get_the_ID() );
if ( $offer_type == 'coupon' ) {
	include( couponxxl_load_path( 'includes/offers/coupon.php' ) );
} else {
	include( couponxxl_load_path( 'includes/offers/deal.php' ) );
}
?>