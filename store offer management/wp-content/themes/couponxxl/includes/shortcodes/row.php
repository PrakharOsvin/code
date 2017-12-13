<?php
function couponxxl_row_func( $atts, $content ){

	return '<div class="row">'.do_shortcode( $content ).'</div>';
}

add_shortcode( 'row', 'couponxxl_row_func' );

function couponxxl_row_params(){
	return array();
}
?>