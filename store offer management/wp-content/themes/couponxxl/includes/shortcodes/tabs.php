<?php
function couponxxl_tabs_func( $atts, $content ){
	extract( shortcode_atts( array(
		'titles' => '',
		'contents' => ''
	), $atts ) );

	$titles = explode( "/n/", $titles );
	$contents = explode( "/n/", $content );

	$titles_html = '';
	$contents_html = '';

	$random = couponxxl_random_string();

	if( !empty( $titles ) ){
		for( $i=0; $i<sizeof( $titles ); $i++ ){
			$titles_html .= '<li role="presentation" class="'.( $i == 0 ? 'active' : '' ).'"><a href="#tab_'.$i.'_'.$random.'" role="tab" data-toggle="tab">'.$titles[$i].'</a></li>';
			$contents_html .= '<div role="tabpanel" class="tab-pane fade '.( $i == 0 ? 'in active' : '' ).'" id="tab_'.$i.'_'.$random.'">'.( !empty( $contents[$i] ) ? apply_filters( 'the_content', $contents[$i] ) : '' ).'</div>';
		}
	}

	return '
	<!-- Nav tabs -->
	<ul class="nav nav-tabs shortcode" role="tablist">
	  '.$titles_html.'
	</ul>

	<!-- Tab panes -->
	<div class="tab-content shortcode">
	  '.$contents_html.'
	</div>';
}

add_shortcode( 'tabs', 'couponxxl_tabs_func' );

function couponxxl_tabs_params(){
	return array(
		array(
			"type" => "textarea",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Titles","couponxxl"),
			"param_name" => "titles",
			"value" => '',
			"description" => esc_html__("Input tab titles separated by /n/.","couponxxl")
		),
		array(
			"type" => "textarea_raw_html",
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Contents","couponxxl"),
			"param_name" => "contents",
			"value" => '',
			"description" => esc_html__("Input tab contents separated by /n/.","couponxxl")
		),

	);
}

if( function_exists( 'vc_map' ) ){
	vc_map( array(
	   "name" => esc_html__("Tabs", 'couponxxl'),
	   "base" => "tabs",
	   "category" => esc_html__('Content', 'couponxxl'),
	   "params" => couponxxl_tabs_params()
	) );
}

?>