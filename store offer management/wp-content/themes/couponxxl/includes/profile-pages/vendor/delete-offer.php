<?php
if( isset( $_GET['offer_id'] ) ){
	$offer = get_post( $_GET['offer_id'] );
	if( !empty( $offer ) && $offer->post_author == $vendor_id ){
		wp_delete_post( $offer->ID );
	}
}
wp_redirect( add_query_arg( array( 'subpage' => $_GET['refferer'] ), $profile_link ) );
?>