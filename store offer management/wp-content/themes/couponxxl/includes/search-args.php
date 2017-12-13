<?php
global $couponxxl_slugs;
$offer_sort = get_query_var( $couponxxl_slugs['offer_sort'], '' );
$offer_type = get_query_var( $couponxxl_slugs['offer_type'], '' );
$offer_store = get_query_var( $couponxxl_slugs['offer_store'], '');
if( !is_array( $offer_store ) ){
	if ( get_option( 'permalink_structure' ) && !empty( $offer_store ) ) {
		$offer_store = explode('--', $offer_store);
	}
	else if( !empty( $offer_store ) ){
		$offer_store = (array)$offer_store;
	}
	else{
		$offer_store = array();
	}
}
$offer_cat = get_query_var( $couponxxl_slugs['offer_cat'], '' );
$offer_tag = get_query_var( $couponxxl_slugs['offer_tag'], '' );
$location = get_query_var( $couponxxl_slugs['location'], '' );
if( !is_array( $location ) ){
	if ( get_option( 'permalink_structure' ) && !empty( $location ) ) {
		$location = explode('--', $location);
	}
	else if( !empty( $location ) ){
		$location = (array)$location;
	}
	else{
		$location = array();
	}
}
$keyword = get_query_var( $couponxxl_slugs['keyword'], '' );
