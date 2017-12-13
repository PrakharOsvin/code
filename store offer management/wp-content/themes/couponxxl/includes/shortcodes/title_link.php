<?php
function couponxxl_title_link_func( $atts, $content ){
	extract( shortcode_atts( array(
		'title' => '',
		'url' => '',
		'text' => '',
	), $atts ) );

	$html = '';
	if( !empty( $title ) ){
		$html .= '<h4>'.$title.'</h4>&nbsp;&nbsp;';
	}
	if( !empty( $url ) && !empty( $text ) ){
		$html .= '<a href="'.esc_url( $url ).'">'.$text.'</a>';
	}

	return $html;
}

add_shortcode( 'title_link', 'couponxxl_title_link_func' );

function couponxxl_title_link_params(){
	return array(
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Title","couponxxl"),
			"param_name" => "title",
			"value" => '',
			"description" => esc_html__("Input title.","couponxxl")
		),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Link URL","couponxxl"),
			"param_name" => "url",
			"value" => '',
			"description" => esc_html__("Input url which will be applied to Link Text.","couponxxl")
		),
		array(
			"type" => "textfield",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Link Text","couponxxl"),
			"param_name" => "text",
			"value" => '',
			"description" => esc_html__("Input text for the link.","couponxxl")
		),
	);
}
?>