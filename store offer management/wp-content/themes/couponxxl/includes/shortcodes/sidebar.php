<?php
function couponxxl_sidebar_func( $atts, $content ){
	extract( shortcode_atts( array(
		'home_sidebar' => '',
	), $atts ) );

	ob_start();
	if( is_active_sidebar( $home_sidebar ) ){
		dynamic_sidebar( $home_sidebar );
	}
	$content = ob_get_contents();
	ob_end_clean();

	return $content;
}

add_shortcode( 'sidebar', 'couponxxl_sidebar_func' );

function couponxxl_sidebar_params(){
	$home_sidebars = couponxxl_get_option( 'home_sidebars' );
	if( empty( $home_sidebars ) ){
		$home_sidebars = 2;
	}

	$sidebars = array();

	for( $i=1; $i<=$home_sidebars; $i++ ){
		$sidebars[esc_html__( 'Home Sidebar ', 'couponxxl' ).$i] = 'home-sidebar-'.$i;
	}
	return array(
		array(
			"type" => "dropdown",
			"value" => $sidebars,
			"holder" => "div",
			"class" => "",
			"heading" => esc_html__("Home Sidebar","couponxxl"),
			"param_name" => "home_sidebar",
			"description" => esc_html__("Select Sidebar To Show","couponxxl")
		),
	);
}
?>