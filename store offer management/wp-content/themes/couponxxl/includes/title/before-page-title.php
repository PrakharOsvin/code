<?php
$page_title_style = couponxxl_get_option( 'page_title_style' );
$style = '';
if( $page_title_style == 'image' ){
	$page_title_bg_image = couponxxl_get_option( 'page_title_bg_image' );
	if( !empty( $page_title_bg_image['url'] ) ){
		$style = 'background-image: url('.esc_url( $page_title_bg_image['url'] ).')';
	}
}
?>
<section class="page-title style-<?php echo esc_attr( $page_title_style ) ?>" style="<?php echo  $style ?>">
	<div class="container">