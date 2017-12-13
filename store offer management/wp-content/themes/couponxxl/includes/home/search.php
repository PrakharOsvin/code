<?php
$home_search_bg_image = couponxxl_get_option( 'home_search_bg_image' );
$home_search_subtitle = couponxxl_get_option( 'home_search_subtitle' );
$home_search_title = couponxxl_get_option( 'home_search_title' );
$home_search_input_placeholder = couponxxl_get_option( 'home_search_input_placeholder' );
$home_search_btn_text = couponxxl_get_option( 'home_search_btn_text' );
$style = '';
if( !empty( $home_search_bg_image['url'] ) ){
	$style = 'background-image: url('.$home_search_bg_image['url'].')';
}
global $couponxxl_slugs;
?>
<section class="home-search" style="<?php echo  $style ?>">
	<div class="container text-center">
		<form class="home-search-form" action="<?php echo esc_url( couponxxl_get_permalink_by_tpl( 'page-tpl_search' ) ) ?>" autocomplete="off">
			<?php echo !empty( $home_search_subtitle ) ? '<p>'.$home_search_subtitle.'</p>' : '' ?>
			<?php echo !empty( $home_search_title ) ? '<p>'.$home_search_title.'</p>' : '' ?>
			<div class="search-input-wrapper">
				<input type="text" placeholder="<?php echo esc_attr( $home_search_input_placeholder ) ?>" name="<?php echo esc_attr( $couponxxl_slugs['keyword'] ) ?>">
				<i class="pline-magnifier"></i>
				<div class="keyword_suggest_stores"></div>
			</div>
			<a href="javascript:;" class="submit-form btn"><?php echo  $home_search_btn_text ?></a>
		</form>
	</div>
</section>