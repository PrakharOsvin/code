<?php
function couponxxl_coupons_func( $atts, $content ){
	ob_start();
	couponxxl_offer_listing_shortcode( $atts );
	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}

add_shortcode( 'coupons', 'couponxxl_coupons_func' );

function couponxxl_coupons_params(){
	return array(
		array(
			"type" => "multidropdown",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Coupon Category","couponxxl"),
			"param_name" => "categories",
			"value" => couponxxl_get_custom_tax_list( 'offer_cat', 'left' ),
			"description" => esc_html__("Filter coupons by category.","couponxxl")
		),
		array(
			"type" => "multidropdown",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Coupon Location","couponxxl"),
			"param_name" => "locations",
			"value" => couponxxl_get_custom_tax_list( 'location', 'left' ),
			"description" => esc_html__("Filter coupons by location.","couponxxl")
		),
		array(
			"type" => "multidropdown",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Coupon Store","couponxxl"),
			"param_name" => "stores",
			"value" => couponxxl_get_custom_list( 'store', array(), '', 'left' ),
			"description" => esc_html__("Filter coupons by store.","couponxxl")
		),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Number Of Coupons ( Used By Filter )","couponxxl"),
			"param_name" => "number",
			"value" => "",
			"description" => esc_html__("Input number of coupons you wish to show which is used for the filter ( -1 for all ).","couponxxl")
		),		
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Coupons","couponxxl"),
			"param_name" => "items",
			"value" => "",
			"description" => esc_html__("Input comma separated list of the coupon IDs to display","couponxxl")
		),
		array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Order By","couponxxl"),
			"param_name" => "orderby",
			"value" => array(
				esc_html__( 'Expire Time', 'couponxxl' ) => 'offer_expire',
				esc_html__( 'Date Added', 'couponxxl' ) => 'date',
				esc_html__( 'Title', 'couponxxl' ) => 'title',
			),
			"description" => esc_html__("Select by which field to order coupons","couponxxl")
		),
		array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Order","couponxxl"),
			"param_name" => "order",
			"value" => array(
				esc_html__( 'Ascending', 'couponxxl' ) => 'ASC',
				esc_html__( 'Descending', 'couponxxl' ) => 'DESC',
			),
			"description" => esc_html__("Select how to order coupons","couponxxl")
		),
		array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Items Per Row","couponxxl"),
			"param_name" => "items_per_row",
			"value" => array(
				'3' => '3',
				'4' => '4',
			),
			"description" => esc_html__("Select how many items per row","couponxxl")
		),
	);
}

if( function_exists( 'vc_map' ) ){
	vc_map( array(
	   "name" => esc_html__("Coupons", 'couponxxl'),
	   "base" => "coupons",
	   "category" => esc_html__('Content', 'couponxxl'),
	   "params" => couponxxl_coupons_params()
	) );
}

?>