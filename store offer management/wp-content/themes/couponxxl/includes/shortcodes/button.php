<?php
function couponxxl_button_func( $atts, $content ){
	extract( shortcode_atts( array(
		'text' => '',
		'link' => '',
		'target' => '',
		'bg_color' => '',
		'bg_color_hvr' => '',
		'font_color' => '',
		'font_color_hvr' => '',
	), $atts ) );

	$rnd = couponxxl_random_string();

	$style_css = '
	<style>
		a.'.$rnd.', a.'.$rnd.':active, a.'.$rnd.':visited, a.'.$rnd.':focus{
			'.( !empty( $bg_color ) ? 'background-color: '.$bg_color.';' : '' ).'
			'.( !empty( $font_color ) ? 'color: '.$font_color.';' : '' ).'
		}
		a.'.$rnd.':hover{
			'.( !empty( $bg_color_hvr ) ? 'background-color: '.$bg_color_hvr.';' : '' ).'
			'.( !empty( $font_color_hvr ) ? 'color: '.$font_color_hvr.';' : '' ).'
		}		
	</style>
	';

	return couponxxl_shortcode_style( $style_css ).'
		<a href="'.esc_url( $link ).'" class="btn '.$rnd.'" target="'.esc_attr( $target ).'">
			'.$text.'
		</a>';
}

add_shortcode( 'button', 'couponxxl_button_func' );

function couponxxl_button_params(){
	return array(
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Button Text","couponxxl"),
			"param_name" => "text",
			"value" => '',
			"description" => esc_html__("Input button text.","couponxxl")
		),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Button Link","couponxxl"),
			"param_name" => "link",
			"value" => '',
			"description" => esc_html__("Input button link.","couponxxl")
		),
		array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Select Window","couponxxl"),
			"param_name" => "target",
			"value" => array(
				esc_html__( 'Same Window', 'couponxxl' ) => '_self',
				esc_html__( 'New Window', 'couponxxl' ) => '_blank',
			),
			"description" => esc_html__("Select window where to open the link.","couponxxl")
		),
		array(
			"type" => "colorpicker",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Background Color","couponxxl"),
			"param_name" => "bg_color",
			"value" => '',
			"description" => esc_html__("Select button background color.","couponxxl")
		),
		array(
			"type" => "colorpicker",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Background Color On Hover","couponxxl"),
			"param_name" => "bg_color_hvr",
			"value" => '',
			"description" => esc_html__("Select button background color on hover.","couponxxl")
		),
		array(
			"type" => "colorpicker",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Font Color","couponxxl"),
			"param_name" => "font_color",
			"value" => '',
			"description" => esc_html__("Select button font color.","couponxxl")
		),
		array(
			"type" => "colorpicker",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Font Color On Hover","couponxxl"),
			"param_name" => "font_color_hvr",
			"value" => '',
			"description" => esc_html__("Select button font color on hover.","couponxxl")
		),
	);
}

if( function_exists( 'vc_map' ) ){
	vc_map( array(
	   "name" => esc_html__("Button", 'couponxxl'),
	   "base" => "button",
	   "category" => esc_html__('Content', 'couponxxl'),
	   "params" => couponxxl_button_params()
	) );
}

?>