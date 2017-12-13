<?php
function couponxxl_gap_func( $atts, $content ){
	extract( shortcode_atts( array(
		'height' => '',
	), $atts ) );

	return '<span style="height: '.esc_attr( $height ).'; display: block;"></span>';
}

add_shortcode( 'gap', 'couponxxl_gap_func' );

function couponxxl_gap_params(){
	return array(
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Gap Height","couponxxl"),
			"param_name" => "height",
			"value" => '',
			"description" => esc_html__("Input gap height.","couponxxl")
		),
	);
}

if( function_exists( 'vc_map' ) ){
	vc_map( array(
	   "name" => esc_html__("Gap", 'couponxxl'),
	   "base" => "gap",
	   "category" => esc_html__('Content', 'couponxxl'),
	   "params" => couponxxl_gap_params()
	) );
}
?>