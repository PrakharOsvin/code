<?php
function couponxxl_iframe_func( $atts, $content ){
	extract( shortcode_atts( array(
		'link' => '',
		'proportion' => '',
	), $atts ) );

	$random = couponxxl_random_string();

	return '
		<div class="embed-responsive embed-responsive-'.$proportion.'">
		  <iframe class="embed-responsive-item" src="'.esc_url( $link ).'"></iframe>
		</div>';
}

add_shortcode( 'iframe', 'couponxxl_iframe_func' );

function couponxxl_iframe_params(){
	return array(
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Iframe link","couponxxl"),
			"param_name" => "link",
			"value" => '',
			"description" => esc_html__("Input link you want to embed.","couponxxl")
		),
		array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Iframe Proportion","couponxxl"),
			"param_name" => "proportion",
			"value" => array(
				esc_html__( '4 by 3', 'couponxxl' ) => '4by3',
				esc_html__( '16 by 9', 'couponxxl' ) => '16by9',
			),
			"description" => esc_html__("Select iframe proportion.","couponxxl")
		),

	);
}

if( function_exists( 'vc_map' ) ){
	vc_map( array(
	   "name" => esc_html__("Iframe", 'couponxxl'),
	   "base" => "iframe",
	   "category" => esc_html__('Content', 'couponxxl'),
	   "params" => couponxxl_iframe_params()
	) );
}

?>