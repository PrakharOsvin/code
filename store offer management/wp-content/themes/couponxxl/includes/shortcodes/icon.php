<?php
function couponxxl_icon_func( $atts, $content ){
	extract( shortcode_atts( array(
		'icon' => '',
		'color' => '',
		'size' => '',
	), $atts ) );

	return '<span class="fa fa-'.$icon.'" style="color: '.$color.'; font-size: '.$size.'; margin: 0px 2px;"></span>';
}

add_shortcode( 'icon', 'couponxxl_icon_func' );

function couponxxl_icon_params(){
	return array(
		array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Select Icon","couponxxl"),
			"param_name" => "icon",
			"value" => couponxxl_awesome_icons_list(),
			"description" => esc_html__("Select an icon you want to display.","couponxxl")
		),
		array(
			"type" => "colorpicker",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Icon Color","couponxxl"),
			"param_name" => "color",
			"value" => '',
			"description" => esc_html__("Select color of the icon.","couponxxl")
		),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Icon Size","couponxxl"),
			"param_name" => "size",
			"value" => '',
			"description" => esc_html__("Input size of the icon.","couponxxl")
		),

	);
}

if( function_exists( 'vc_map' ) ){
	vc_map( array(
	   "name" => esc_html__("Icon", 'couponxxl'),
	   "base" => "icon",
	   "category" => esc_html__('Content', 'couponxxl'),
	   "params" => couponxxl_icon_params()
	) );
}

?>