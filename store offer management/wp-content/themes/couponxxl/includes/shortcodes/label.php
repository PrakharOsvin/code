<?php
function couponxxl_label_func( $atts, $content ){
	extract( shortcode_atts( array(
		'text' => '',
		'bg_color' => '',
		'font_color' => '',
	), $atts ) );

	return '<span class="label label-default" style="color: '.$font_color.'; background-color: '.$bg_color.'">'.$text.'</span>';
}

add_shortcode( 'label', 'couponxxl_label_func' );

function couponxxl_label_params(){
	return array(
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Text","couponxxl"),
			"param_name" => "text",
			"value" => '',
			"description" => esc_html__("Input label text.","couponxxl")
		),
		array(
			"type" => "colorpicker",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Background Color Color","couponxxl"),
			"param_name" => "bg_color",
			"value" => '',
			"description" => esc_html__("Select background color of the label.","couponxxl")
		),
		array(
			"type" => "colorpicker",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Text Color","couponxxl"),
			"param_name" => "font_color",
			"value" => '',
			"description" => esc_html__("Select font color for the label text.","couponxxl")
		),

	);
}

if( function_exists( 'vc_map' ) ){
	vc_map( array(
	   "name" => esc_html__("Label", 'couponxxl'),
	   "base" => "label",
	   "category" => esc_html__('Content', 'couponxxl'),
	   "params" => couponxxl_label_params()
	) );
}

?>