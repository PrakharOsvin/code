<?php
global $couponxxl_slugs;
include( couponxxl_load_path( 'includes/search-args.php' ) );
?>
<form method="get" action="<?php echo esc_url( couponxxl_get_permalink_by_tpl( 'page-tpl_search' ) ) ?>" autocomplete="off">
	<input type="text" name="<?php echo esc_attr($couponxxl_slugs['keyword']) ?>" value="<?php echo !empty( $keyword ) ? esc_attr( $keyword ) : '' ?>" class="keyword" placeholder="<?php _e( 'Search discounts and stores', 'couponxxl' ) ?>"/>
	<i class="pline-magnifier"></i>
	<div class="keyword_suggest_stores"></div>
	<a href="javascript:;" class="submit-form btn"><?php esc_html_e( 'Search', 'couponxxl' ) ?></a>
</form>