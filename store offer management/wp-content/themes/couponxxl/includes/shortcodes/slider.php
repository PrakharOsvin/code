<?php
function couponxxl_slider_func( $atts, $content ){
	global $offers;
	extract( shortcode_atts( array(
		'offers' => '',
	), $atts ) );	
	$offers = explode( ',', $offers );

	ob_start();
	include( couponxxl_load_path( 'includes/featured-slider.php' ) );
	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}

add_shortcode( 'slider', 'couponxxl_slider_func' );

function couponxxl_slider_params(){
	return array(
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Offers","couponxxl"),
			"param_name" => "offers",
			"value" => '',
			"description" => esc_html__("Input comma separated list of offers you wish to add to slider. Make sure that the offers have thumbnail image.","couponxxl")
		),
	);
}
?>