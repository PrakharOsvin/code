<form method="get" class="search-form" action="<?php echo site_url('/'); ?>">
	<input type="text" class="form-control" id="search" name="s" placeholder="<?php esc_html_e( 'Type term and hit enter', 'couponxxl' ) ?>">
	<input type="hidden" name="post_type" value="post" />
</form>