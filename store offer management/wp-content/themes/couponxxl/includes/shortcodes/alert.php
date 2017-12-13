<?php
function couponxxl_alert_func( $atts, $content ){
	extract( shortcode_atts( array(
		'text' => '',
		'border_color' => '',
		'bg_color' => '',
		'font_color' => '',
		'icon' => '',
		'closeable' => 'no',
		'close_icon_color' => '',
		'close_icon_color_hvr' => '',
	), $atts ) );

	$rnd = couponxxl_random_string();

	$style_css = '
		<style>
			.'.$rnd.'.alert .close{
				color: '.$close_icon_color.';
			}
			.'.$rnd.'.alert .close:hover{
				color: '.$close_icon_color_hvr.';
			}
		</style>
	';

	return couponxxl_shortcode_style( $style_css ).'
	<div class="alert '.$rnd.' alert-default '.( $closeable == 'yes' ? 'alert-dismissible' : '' ).'" role="alert" style=" color: '.$font_color.'; border-color: '.$border_color.'; background-color: '.$bg_color.';">
		'.( !empty( $icon ) && $icon !== 'No Icon' ? '<i class="fa fa-'.$icon.'"></i>' : '' ).'
		'.$text.'
		'.( $closeable == 'yes' ? '<button type="button" class="close" data-dismiss="alert"> <span aria-hidden="true">×</span> <span class="sr-only">'.esc_html__( 'Close', 'couponxxl' ).'</span> </button>' : '' ).'
	</div>';
}

add_shortcode( 'alert', 'couponxxl_alert_func' );

function couponxxl_alert_params(){
	return array(
		array(
			"type" => "textarea",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Text","couponxxl"),
			"param_name" => "text",
			"value" => '',
			"description" => esc_html__("Input alert text.","couponxxl")
		),
		array(
			"type" => "colorpicker",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Border Color","couponxxl"),
			"param_name" => "border_color",
			"value" => '',
			"description" => esc_html__("Select border color for the alert box.","couponxxl")
		),
		array(
			"type" => "colorpicker",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Background Color Color","couponxxl"),
			"param_name" => "bg_color",
			"value" => '',
			"description" => esc_html__("Select background color of the alert box.","couponxxl")
		),
		array(
			"type" => "colorpicker",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Text Color","couponxxl"),
			"param_name" => "font_color",
			"value" => '',
			"description" => esc_html__("Select font color for the alert box text.","couponxxl")
		),
		array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Icon","couponxxl"),
			"param_name" => "icon",
			"value" => couponxxl_awesome_icons_list(),
			"description" => esc_html__("Select icon.","couponxxl")
		),
		array(
			"type" => "dropdown",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Closeable","couponxxl"),
			"param_name" => "closeable",
			"value" => array(
				esc_html__( 'No', 'couponxxl' ) => 'no',
				esc_html__( 'Yes', 'couponxxl' ) => 'yes'
			),
			"description" => esc_html__("Enable or disable alert closing.","couponxxl")
		),
		array(
			"type" => "colorpicker",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Close Icon Color","couponxxl"),
			"param_name" => "close_icon_color",
			"value" => '',
			"description" => esc_html__("Select color for the close icon.","couponxxl")
		),
		array(
			"type" => "colorpicker",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Close Icon Color On Hover","couponxxl"),
			"param_name" => "close_icon_color_hvr",
			"value" => '',
			"description" => esc_html__("Select color for the close icon on hover.","couponxxl")
		),
	);
}

if( function_exists( 'vc_map' ) ){
	vc_map( array(
	   "name" => esc_html__("Alert", 'couponxxl'),
	   "base" => "alert",
	   "category" => esc_html__('Content', 'couponxxl'),
	   "params" => couponxxl_alert_params()
	) );
}
?>