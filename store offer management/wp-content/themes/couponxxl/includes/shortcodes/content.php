<?php
function couponxxl_content_func( $atts, $content ){

	return '<div class="white-block"><div class="white-block-content">'.apply_filters( 'the_content', $content ).'</div></div>';
}

add_shortcode( 'content', 'couponxxl_content_func' );

function couponxxl_content_params(){
	return array();
}
?>