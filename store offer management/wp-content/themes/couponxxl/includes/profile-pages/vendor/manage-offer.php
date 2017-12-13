<?php
$cxxl_credits = get_user_meta( $vendor_id, 'cxxl_credits', true );
$edit = false;
$offer_id = $_GET['offer_id'];
if( !isset( $_GET['offer_type'] ) ){
	$offer = new WP_Offers_Query(array(
		'posts_per_page' => -1,
		'post__in' => array( $_GET['offer_id'] ),
		'post_status' => 'publish, draft',
	));
	$offer->the_post();
	if( get_the_author_meta('ID') == $vendor_id ){
		$edit = true;
	}
}
$offer_type = $edit ? couponxxl_get_the_offer_type() : $_GET['offer_type'];
if( $offer_type == 'coupon' ){
	$offer_submit_price = couponxxl_get_option( 'coupon_submit_price' );
	$name = esc_html__('coupon', 'couponxxl');
}
else{
	$offer_submit_price = couponxxl_get_option( 'deal_submit_price' );
	$name = esc_html__('deal', 'couponxxl');
}
if( $cxxl_credits >= $offer_submit_price || $edit ):

	$offer_title = $edit ? get_the_title( $offer_id ) : '';
	$offer_description = $edit ? get_post_field( 'post_content', $offer_id ) : '';
	$offer_featured_image = $edit ? get_post_thumbnail_id( $offer_id ) : '';
	$offer_cat_selected = array();
	if( $edit ){
		$categories = get_the_terms( $offer_id, 'offer_cat' );
		if( !empty( $categories ) ){
			foreach( $categories as $category ){
				$offer_cat_selected[] = $category->term_id;
			}
		}
	}
	$offer_new_category = $edit ? get_post_meta( $offer_id, 'offer_new_category', true ) : '';
	$offer_start = $edit ? false : time();
	$offer_expire = $edit ? false : '';

	/*COUPON RELATED */
	if( $offer_type == 'coupon' ){
		$coupon_excerpt = $edit ? get_the_excerpt() : '';
		$coupon_type = $edit ? get_post_meta( $offer_id, 'coupon_type', true ) : '';
		$coupon_code = $edit ? get_post_meta( $offer_id, 'coupon_code', true ) : '';
		$coupon_sale = $edit ? get_post_meta( $offer_id, 'coupon_sale', true ) : '';
		$coupon_image = $edit ? get_post_meta( $offer_id, 'coupon_image', true ) : '';
		$coupon_link = $edit ? get_post_meta( $offer_id, 'coupon_link', true ) : '';
	}
	else{
	/*DEAL REALTED*/
		$deal_excerpt = $edit ? get_the_excerpt() : '';
		$deal_link = $edit ? get_post_meta( $offer_id, 'deal_link', true ) : '';
		$deal_items = $edit ? get_post_meta( $offer_id, 'deal_items', true ) : '';
		$deal_item_vouchers = $edit ? get_post_meta( $offer_id, 'deal_item_vouchers', true ) : '';
		$deal_price = $edit ? get_post_meta( $offer_id, 'deal_price', true ) : '';
		$deal_sale_price = $edit ? get_post_meta( $offer_id, 'deal_sale_price', true ) : '';
		$deal_min_sales = $edit ? get_post_meta( $offer_id, 'deal_min_sales', true ) : '';
		$deal_voucher_expire = $edit ? get_post_meta( $offer_id, 'deal_voucher_expire', true ) : '';
		$deal_markers = $edit ? get_post_meta( $offer_id, 'deal_markers', true ) : '';
		$deal_images = $edit ? get_post_meta( $offer_id, 'deal_images', true ) : '';
		$deal_type = $edit ? get_post_meta( $offer_id, 'deal_type', true ) : '';
	}

	$date_ranges = couponxxl_get_option( 'date_ranges' );
	$unlimited_expire = couponxxl_get_option( 'unlimited_expire' );

	if( !empty( $offer_submit_price ) && !$edit ){
		echo '<div class="alert alert-info">'.esc_html__( 'Submission of the','couponxxl').' '.$name.' '.esc_html__('is charged', 'couponxxl' ).' '.$offer_submit_price.' '.( $offer_submit_price == '1' ? esc_html__( 'credit', 'couponxxl' ) : esc_html__( 'credits', 'couponxxl' ) ).'</div>';
	}
	?>
	<?php if( in_array( $offer_id, $offer_ids ) || ! $edit ) : ?>
	<form method="post">
		<div id="wizard" class="<?php echo $offer_type == 'coupon' ? esc_attr( 'coupon-wizard' ) : esc_attr( '' ) ?>">
		    <h1></h1>
		    <div>
			    <div class="input-group">
			        <label for="offer_title"><?php esc_html_e( 'Offer Title', 'couponxxl' ); ?> <span class="required">*</span></label>
			        <input type="text" name="offer_title" id="offer_title" value="<?php echo esc_attr( $offer_title ) ?>" class="form-control" data-validation="required"  data-error="<?php _e( 'Please input offer description', 'couponxxl' ); ?>">
			        <p class="description"><?php esc_html_e( 'Input title for the offer.', 'couponxxl' ); ?></p>
			    </div>

		        <label for="offer_description" data-error="<?php _e( 'Please type description of the offer', 'couponxxl' ); ?>"><?php esc_html_e( 'Offer Description', 'couponxxl' ); ?> <span class="required">*</span></label>
		        <?php wp_editor( $offer_description, 'offer_description' ); ?>
		        <p class="description"><?php esc_html_e( 'Input description of the offer.', 'couponxxl' ); ?></p>

			    <div class="input-group">
			        <label for="offer_featured_image"><?php esc_html_e( 'Offer Image', 'couponxxl' ); ?> <span class="required">*</span></label>
			        <input type="hidden" name="offer_featured_image" id="offer_featured_image" value="<?php echo esc_attr( $offer_featured_image ) ?>" data-validation="required"  data-error="<?php _e( 'Please input offer presentation image', 'couponxxl' ); ?>">
			        <div class="upload-image-wrap featured-image-wrap">
			        	<?php 
			        	if( !empty( $offer_featured_image ) ){
			        		echo wp_get_attachment_image( $offer_featured_image, 'thumbnail' );
			        	}
			        	?>
			        </div>
			        <a href="javascript:;" class="image-upload featured-image"><?php esc_html_e( 'Select featured image', 'couponxxl' ) ?></a>
			        <p class="description"><?php esc_html_e( 'Upload and select featured image for the offer.', 'couponxxl' ); ?></p>
			    </div>			    
			</div>

			<h1></h1>
			<div>
			    <div class="input-group">
			        <label for="offer_cat"><?php esc_html_e( 'Offer Category', 'couponxxl' ); ?> <span class="required">*</span></label>
			        <select name="offer_cat" id="offer_cat" class="form-control" data-validation="conditional" data-conditional-field="offer_new_category" data-error="<?php _e( 'Please select offer category', 'couponxxl' ); ?>">
			            <option value=""><?php esc_html_e( '- Select -', 'couponxxl' ) ?></option>
			            <?php
			            $categories = couponxxl_get_organized( 'offer_cat' );

			            if( !empty( $categories ) ){
			            	foreach( $categories as $key => $category){
			            		couponxxl_display_select_tree( $category, $offer_cat_selected );
			            	}
			            }
			            ?>
			        </select>
			        <p class="description"><?php esc_html_e( 'Select category for the offer or populate field bellow to request new one leaving this field not selected.', 'couponxxl' ); ?></p>
			    </div>
			    <div class="input-group">
			        <label for="offer_new_category"><?php esc_html_e( 'Category not listed?', 'couponxxl' ); ?></label>
			        <textarea name="offer_new_category" id="offer_new_category" class="form-control" ><?php echo  $offer_new_category; ?></textarea>
			        <p class="description"><?php esc_html_e( 'Populate this field if desired category is not listed in the previous field.', 'couponxxl' ); ?></p>
			    </div>
			</div>

			<?php if( !$edit ): ?>
				<h1></h1>
				<div>
				    <div class="input-group">
				        <label for="offer_start"><?php esc_html_e( 'Offer Start Date', 'couponxxl' ); ?> </label>
				        <input type="text" name="offer_start" id="offer_start" value="<?php echo date( 'Y-m-d', $offer_start ) ?>" class="form-control" readonly="readonly" data-range="<?php echo esc_attr( $date_ranges ); ?>">
				        <p class="description"><?php esc_html_e( 'Set start date for the offer. If this field is empty current time will be applied to the offer.', 'couponxxl' ); ?></p>
				    </div>

				    <div class="input-group">
				        <label for="offer_expire"><?php esc_html_e( 'Offer Expire Date', 'couponxxl' ); ?> <span class="required">*</span></label>
				        <input type="text" name="offer_expire" id="offer_expire" value="<?php echo !empty( $offer_expire ) && $offer_expire !== '99999999999' ? date( 'Y-m-d', $offer_expire ) : '' ?>" class="form-control" readonly="readonly" data-range="<?php echo esc_attr( $date_ranges ); ?>" <?php echo !empty( $date_ranges ) ? 'data-validation="required"  data-error="'.esc_attr__( 'Please input offer expiration date', 'couponxxl' ).'"' : ''; ?> >
				        <p class="description"><?php esc_html_e( 'Set expire date for the offer.', 'couponxxl' ); ?></p>
				    </div>
				</div>
			<?php endif; ?>

		    <?php
		    if( $offer_type == 'coupon' ){
		    	?>
		    	<h1></h1>
		    	<div>
		    	    <?php include( couponxxl_load_path( 'includes/profile-pages/vendor/coupon-submit.php' ) ); ?>
		    	</div>
		    	<?php
		    }
		    else{
		    	include( couponxxl_load_path( 'includes/profile-pages/vendor/deal-submit.php' ) );
		    }
		    ?>
	    	<input type="hidden" name="offer_type" value="<?php echo esc_attr( $offer_type ); ?>">
	    	<?php if( $edit ): ?>
	    		<input type="hidden" name="offer_id" value="<?php echo esc_attr( $_GET['offer_id'] ); ?>">
	    	<?php endif; ?>
	    	<input type="hidden" name="action" value="insert_offer">
	    	<input type="hidden" name="offer_store" value="<?php echo esc_attr( $store->ID ) ?>">
	    	<input type="hidden" name="vendor_id" value="<?php echo esc_attr( $vendor_id ) ?>">
	    </div>
	</form>
	<?php else : ?>
		<div class="alert alert-danger"><?php esc_html_e( 'You\'re trying to reach things you shouldn\'t!', 'couponxxl'); ?></div>
	<?php endif; ?>
	<?php
	if( $edit ){
		wp_reset_postdata();
	}
	?>
<?php else: ?>
	<div class="alert alert-danger"><?php esc_html_e( 'You do not have enough credits on your account to submit this offer', 'couponxxl' ) ?></div>
<?php endif; ?>