<div class="white-block">
	<div class="white-block-content">
		<form method="post">
			<div class="row">
				<div class="col-sm-6">
					<div class="input-group">
						<label for="store_title"><?php esc_html_e( 'Store Name', 'couponxxl' ); ?> <span
								class="required">*</span></label>
						<input type="text" name="store_title" id="store_title" class="form-control"
						       data-validation="required"
						       data-error="<?php _e( 'Please input store name', 'couponxxl' ); ?>"
						       value="<?php echo esc_attr( $store->post_title ) ?>">
						<i class="pline-user"></i>
					</div>
					<div class="input-group">
						<label for="store_facebook"><?php esc_html_e( 'Store Facebook Link', 'couponxxl' ); ?> </label>
						<input type="text" name="store_facebook" id="store_facebook" class="form-control"
						       value="<?php echo esc_attr( get_post_meta( $store->ID, 'store_facebook', true ) ) ?>">
						<i class="pline-lock-locked"></i>
					</div>
					<div class="input-group">
						<label for="store_twitter"><?php esc_html_e( 'Store Twitter Link', 'couponxxl' ); ?> </label>
						<input type="text" name="store_twitter" id="store_twitter" class="form-control"
						       value="<?php echo esc_attr( get_post_meta( $store->ID, 'store_twitter', true ) ) ?>">
						<i class="pline-lock-locked"></i>
					</div>
				</div>
				<div class="col-sm-6">
					<div class="input-group">
						<label for="store_link"><?php esc_html_e( 'Store Link', 'couponxxl' ); ?> </label>
						<input type="text" name="store_link" id="store_link" class="form-control"
						       value="<?php echo esc_attr( get_post_meta( $store->ID, 'store_link', true ) ) ?>">
						<i class="pline-chain-alt"></i>
					</div>
					<div class="input-group">
						<label for="store_google"><?php esc_html_e( 'Store Google+ Link', 'couponxxl' ); ?> </label>
						<input type="text" name="store_google" id="store_google" class="form-control"
						       value="<?php echo esc_attr( get_post_meta( $store->ID, 'store_google', true ) ) ?>">
						<i class="pline-lock-locked"></i>
					</div>
					<div class="input-group">
						<label for="store_rss"><?php esc_html_e( 'Store RSS Link', 'couponxxl' ); ?> </label>
						<input type="text" name="store_rss" id="store_rss" class="form-control"
						       value="<?php echo esc_attr( get_post_meta( $store->ID, 'store_rss', true ) ) ?>">
						<i class="pline-lock-locked"></i>
					</div>
				</div>
				<div class="col-sm-12">
					<label for="store_description"><?php esc_html_e( 'Store Description', 'couponxxl' ); ?> </label>
					<?php wp_editor( $store->post_content, 'store_description' ); ?>
				</div>
				<div class="col-sm-12">
					<div class="input-group-wrap">
						<label for="store_locations"><?php esc_html_e( 'Store Locations', 'couponxxl' ); ?> </label>
					</div>
				</div>
				<div class="col-sm-12">
					<input type="hidden" value="update_store" name="action">
					<input type="hidden" value="<?php echo esc_attr( $store->ID ); ?>" name="store_id">
					<?php couponxxl_gmap_location_callback( $store ); ?>
				</div>
				<div class="col-sm-12">
					<?php wp_nonce_field( 'update_store', 'update_store_field' ); ?>
					<a href="javascript:;"
					   class="btn submit-form ajax-return"><?php esc_html_e( 'Update Store', 'couponxxl' ); ?></a>
					<div class="ajax-response"></div>
				</div>
			</div>
		</form>
	</div>
</div>

